<?php

namespace App\Service\Logic;

use App\Entity\ReportData;
use GuzzleHttp;

class OrderStatisticLogic extends BaseOrderLogic
{
    const REPORT_HEADER = ["status", "Order"];

    /**
     * @return ReportData
     */
    public function getReportData(): ReportData
    {
        $orderStatus = $this->getOrderStatus();
        $orderList = $this->getOrderList();
        $result = $this->generateOrderData($orderStatus, $orderList);

        return new ReportData(self::REPORT_HEADER, $result);
    }

    /**
     * @param $orderStatus
     * @param $orderList
     * @return array
     */
    public function generateOrderData($orderStatus, $orderList)
    {
        $orderStatusList = [];

        // fetch order status
        if ($orderStatus) {
            array_map(
                function ($status) use (&$orderStatusList) {
                    $orderStatusList[$status['id']] = [
                        "description" => $status['description'],
                        "numberOfOrder" => 0
                    ];
                },
                $orderStatus
            );
        }

        // calculate number of order
        if ($orderList) {
            array_map(
                function ($order) use (&$orderStatusList) {
                    $orderStatus = $order['status'];
                    if (key_exists($orderStatus, $orderStatusList) && $orderStatusList[$orderStatus]) {
                        $orderStatusList[$orderStatus]['numberOfOrder']++;
                    }
                },
                $orderList
            );
        }

        // sort order list
        array_multisort(array_column($orderStatusList, "numberOfOrder"), SORT_DESC, SORT_NUMERIC, $orderStatusList);

        return $orderStatusList;
    }
}
