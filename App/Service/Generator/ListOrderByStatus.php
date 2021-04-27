<?php

namespace App\Service\Generator;

use App\Entity\ReportData;
use App\Service\DataProvider\FixablyApi;
use App\Service\Logic\OrderStatisticLogic;
use Exception;

class ListOrderByStatus extends BaseOrderReport
{
    private FixablyApi $api;
    private OrderStatisticLogic $logic;

    /**
     * ListOrderByStatus constructor.
     * @param FixablyApi $api
     * @param OrderStatisticLogic $logic
     */
    public function __construct(
        FixablyApi $api,
        OrderStatisticLogic $logic
    ) {
        $this->api = $api;
        $this->logic = $logic;
    }

    /**
     * @return ReportData
     */
    public function getReportData(): ReportData
    {
        $this->logic->setOrderList($this->api->getOrderList());
        $this->logic->setOrderStatus($this->api->getOrderStatus());
        return $this->logic->getReportData();
    }
}
