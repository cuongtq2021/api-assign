<?php
namespace App\Service\Generator;
use App\Entity\ReportData;

interface ReportInterface {
    /**
     * return the report which contains column and row
     * @return ReportData
     */
    public function generateReport(): ReportData;
}
