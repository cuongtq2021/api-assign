<?php

namespace App\Service\Generator;

use App\Entity\ReportData;
use App\Service\DataProvider\FixablyApi;
use App\Service\Logic\AssignedIphoneOrderListLogic;
use Exception;

class ListAssignedIphoneOrder extends BaseOrderReport
{
    private FixablyApi $api;
    private AssignedIphoneOrderListLogic $logic;

    /**
     * ListAssignedIphoneOrder constructor.
     * @param FixablyApi $api
     * @param AssignedIphoneOrderListLogic $logic
     */
    public function __construct(
        FixablyApi $api,
        AssignedIphoneOrderListLogic $logic
    ) {
        $this->api = $api;
        $this->logic = $logic;
    }

    /**
     * @return ReportData
     */
    public function getReportData(): ReportData
    {
        $this->logic->setOrderList($this->api->getIphoneOrderList());
        return $this->logic->getReportData();
    }
}
