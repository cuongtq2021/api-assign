<?php

namespace App\Service\DataProvider;

use Exception;
use GuzzleHttp\Client;
use Throwable;

class FixablyApi implements DataProviderInterface
{
    const GET_METHOD = "GET";
    const POST_METHOD = "POST";
    const RESULT_KEY = "results";

    private Client $httpClient;
    private string $baseEndpoint;
    private string $code;

    /**
     * FixablyApi constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client(['verify' => false]);
        $this->baseEndpoint = $_ENV['BASE_ENDPOINT'];
        $this->code = $_ENV['CODE'];
    }

    /**
     * @return array|string
     */
    public function getOrderList(): ?array
    {
        try {
            $result = [];
            $ordersData = $this->getOrderListPerPage();
            $numberOfOrderPage = $this->getNumberOfPage($ordersData);

            for ($page = 1; $page < $numberOfOrderPage; $page++) {
                $ordersData = $this->getOrderListPerPage($page);

                if ($ordersData && key_exists(self::RESULT_KEY, $ordersData)) {
                    array_map(
                        function ($order) use (&$result) {
                            $result[] = $order;
                        },
                        $ordersData[self::RESULT_KEY]
                    );
                }
            }

            return $result;
        } catch (Throwable $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @param int $page
     * @return mixed|string
     */
    protected function getOrderListPerPage($page = 1)
    {
        return $this->buildRequest("orders", self::GET_METHOD, $page);
    }

    /**
     * @param $endpoint
     * @param $method
     * @param null $page
     * @param array $extraOptions
     * @param null $token
     * @return mixed|string
     */
    protected function buildRequest($endpoint, $method, $page = null, $extraOptions = [], $token = null)
    {
        if (!$token) {
            $token = $this->getToken();
        }

        $options["headers"] = [
            $_ENV['FIXABLY_TOKEN_HEADER'] => $token
        ];

        if ($page) {
            $options["query"] = ["page" => $page];
        }

        if (count($extraOptions) > 0) {
            $options['multipart'] = $extraOptions;
        }

        try {
            $result = $this->httpClient->request(
                $method,
                $this->baseEndpoint . $endpoint,
                $options
            );
            return json_decode($result->getBody(), true);
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @return string
     */
    public function getToken()
    {
        try {
            $bodyData = [
                'Code' => $this->code,
            ];

            $result = $this->httpClient->request(
                'POST',
                $this->baseEndpoint . "token",
                [
                    "form_params" => $bodyData
                ]
            );

            $resultBodyData = json_decode($result->getBody(), true);

            $retry = false;

            // re try to get token if the first time is not working
            if (!$this->isTokenValid($resultBodyData)) {
                if (!$retry) {
                    $this->getToken();
                    $retry = true;
                } else {
                    throw new Exception('Can not get API token');
                }
            }

            return (string)$resultBodyData['token'];
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param array $tokenData
     * @return bool
     */
    protected function isTokenValid(array $tokenData)
    {
        return key_exists('token', $tokenData);
    }

    /**
     * @param array $listData
     * @return int
     */
    protected function getNumberOfPage(array $listData)
    {
        return (int)ceil($listData['total'] / 10);
    }

    /**
     *
     * @return array
     */
    public function getIphoneOrderList()
    {
        try {
            $result = [];
            $ordersData = $this->getIphoneOrderListPerPage();

            $numberOfOrderPage = $this->getNumberOfPage($ordersData);

            for ($page = 1; $page < $numberOfOrderPage; $page++) {
                $ordersData = $this->getIphoneOrderListPerPage($page);

                if ($ordersData && key_exists(self::RESULT_KEY, $ordersData)) {
                    array_map(
                        function ($order) use (&$result) {
                            $result[] = $order;
                        },
                        $ordersData[self::RESULT_KEY]
                    );
                }
            }

            return $result;
        } catch (Throwable $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @param int $page
     * @return mixed|string
     */
    protected function getIphoneOrderListPerPage($page = 1)
    {
        try {
            $filterOptions = [
                [
                    'name' => 'Criteria',
                    'contents' => 'Iphone X'
                ]
            ];

            return $this->buildRequest("search/devices", self::POST_METHOD, $page, $filterOptions);
        } catch (Throwable $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @param $data
     * @return array|int
     */
    public function getInvoiceList(string $data)
    {
        try {
            $result = [];
            $invoicePage = $this->getInvoiceListByPage($data);

            if (key_exists("error", $invoicePage)) {
                return 0;
            }

            $numberOfOrderPage = $this->getNumberOfPage($invoicePage);

            for ($page = 1; $page < $numberOfOrderPage; $page++) {
                $invoiceData = $this->getInvoiceListByPage($data, $page);

                if ($invoiceData && key_exists(self::RESULT_KEY, $invoiceData)) {
                    array_map(
                        function ($invoice) use (&$result) {
                            $result[] = $invoice;
                        },
                        $invoiceData[self::RESULT_KEY]
                    );
                }
            }
            return $result;
        } catch (Throwable $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @param string $data
     * @param int $page
     * @return mixed|string
     */
    protected function getInvoiceListByPage(string $data, $page = 1)
    {
        return $this->buildRequest("report" . $data, self::POST_METHOD, $page);
    }

    /**
     * @return mixed|string
     */
    public function getOrderStatus()
    {
        try {
            return $this->buildRequest("statuses", self::GET_METHOD);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @return string
     */
    public function createOrder(): string
    {
        try {
            $token = $this->getToken();

            $newOrderId = $this->createNewOrder($token);

            if (!$newOrderId) {
                return "There is a bug during create a new order";
            }

            $updateOrderParameters = [
                [
                    "name" => "Type",
                    "contents" => "Issue"
                ],
                [
                    "name" => "Description",
                    "contents" => "Broken screen"
                ]
            ];

            $response = $this->updateOrder($newOrderId, $updateOrderParameters, $token);

            $orderDetail = $this->getOrderById($newOrderId);

            if ($orderDetail) {
                $response = sprintf("%s, at %s", $response, $orderDetail['created']);
            }

            return $response;
        } catch (Throwable $exception) {
            return sprintf(
                "%s: %s",
                "There is a bug during create a new order, please contact with us and enclose this error message: ",
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $token
     * @return false|mixed|string
     */
    protected function createNewOrder(string $token)
    {
        $orderParameters = [
            [
                "name" => "DeviceManufacturer",
                "contents" => "Apple"
            ],
            [
                "name" => "DeviceBrand",
                "contents" => "MacBook Pro"
            ],
            [
                "name" => "DeviceType",
                "contents" => "Laptop"
            ]
        ];

        $response = $this->buildRequest("orders/create", self::POST_METHOD, null, $orderParameters, $token);

        if (key_exists("id", $response) && ("Order created" === $response['message'])) {
            return $response['id'];
        }

        return false;
    }

    /**
     * @param string $orderId
     * @param array $orderParameters
     * @param string $token
     * @return string
     */
    protected function updateOrder(string $orderId, array $orderParameters, string $token): string
    {
        $response = $this->buildRequest(
            sprintf("orders/%s/notes/create", $orderId),
            self::POST_METHOD,
            null,
            $orderParameters,
            $token
        );

        if (key_exists("id", $response) && ("Note created" === $response['message'])) {
            return sprintf("A new order with id: %s has been create", $orderId);
        }

        if (key_exists("error", $response)) {
            return sprintf("There is a bug when update order: ", $response['error']);
        }
    }

    /**
     * @param string $orderId
     * @return array|false
     */
    protected function getOrderById(string $orderId)
    {
        $response = $this->buildRequest(sprintf("orders/%s", $orderId), self::GET_METHOD);

        if (is_array($response) && key_exists("id", $response)) {
            return $response;
        }

        return false;
    }

}
