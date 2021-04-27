<?php
namespace App\Service\Logic;
use App\Entity\ReportData;

interface LogicInterface {
    /**
     * @return ReportData
     */
    public function getReportData(): ReportData;
}
