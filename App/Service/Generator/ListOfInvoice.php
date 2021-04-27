<?php

namespace App\Service\Generator;

use App\Entity\ReportData;
use App\Service\DataProvider\FixablyApi;
use App\Service\Logic\InvoicesLogic;

class ListOfInvoice extends BaseInvoiceReport
{
    private FixablyApi $api;
    private InvoicesLogic $logic;

    /**
     * ListOfInvoice constructor.
     * @param FixablyApi $api
     * @param InvoicesLogic $logic
     */
    public function __construct(
        FixablyApi $api,
        InvoicesLogic $logic
    ) {
        $this->api = $api;
        $this->logic = $logic;
    }

    /**
     * @return ReportData
     */
    public function getReportData(): ReportData
    {
        $this->logic->setInvoiceList($this->getInvoiceByDate());
        return $this->logic->getReportData();
    }

    /**
     * @return array
     */
    public function getInvoiceByDate()
    {
        $result = [];

        $dateRage = $this->getWeekRage();

        array_map(
            function ($date) use (&$result) {
                $fromDate = $date[0];
                $toDate = $date[1];
                $dateRage = "/" . $fromDate . "/" . $toDate;
                $result[] =
                    [
                        "from" => $fromDate,
                        "to" => $toDate,
                        "result" => $this->api->getInvoiceList($dateRage)
                    ];
            },
            $dateRage
        );

        return $result;
    }

    /**
     * Hard code for week
     * @return array
     */
    protected function getWeekRage(): array
    {
        return [
            ["2020-11-01", "2020-11-02"],
            ["2020-11-02", "2020-11-08"],
            ["2020-11-09", "2020-11-15"],
            ["2020-11-16", "2020-11-22"],
            ["2020-11-23", "2020-11-29"],
            ["2020-11-29", "2020-11-30"],
        ];
    }
}
