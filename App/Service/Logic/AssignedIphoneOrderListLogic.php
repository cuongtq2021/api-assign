<?php

namespace App\Service\Logic;

use App\Entity\ReportData;

class AssignedIphoneOrderListLogic extends BaseOrderLogic
{

    const REPORT_HEADER = ["status", "Order"];

    /**
     * @return ReportData
     */
    public function getReportData(): ReportData
    {
        $orderList = $this->getOrderList();
        $result = $this->filterOrderData($orderList);

        return new ReportData(self::REPORT_HEADER, $result);
    }

    /**
     * @param array $orderList
     * @return array
     */
    public function filterOrderData(array $orderList)
    {
        $result = [];

        if ($orderList) {
            $result = array_filter(
                $orderList,
                function ($order) use (&$result) {
                    return 3 === $order['status'];
                }
            );
        }

        return $result;
    }
}
