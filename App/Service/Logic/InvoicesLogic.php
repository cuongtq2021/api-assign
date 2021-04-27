<?php

namespace App\Service\Logic;

use App\Entity\ReportData;
use GuzzleHttp;

class InvoicesLogic extends BaseInvoiceLogic
{

    const REPORT_HEADER = ["Date", "Total invoice", "Total invoice amount", ""];

    /**
     * @return ReportData
     */
    public function getReportData(): ReportData
    {
        $listOfInvoice = $this->getListOfInvoice();

        return new ReportData(self::REPORT_HEADER, $listOfInvoice);
    }

    /**
     * @return mixed
     */
    private function getListOfInvoice()
    {
        $result = [];
        $invoiceList = $this->getInvoiceList();

        for ($index = 0; $index < count($invoiceList); $index++) {
            $currentInvoiceWeek = $invoiceList[$index];
            $time = sprintf("%s - %s", $currentInvoiceWeek["from"], $currentInvoiceWeek["to"]);
            $invoiceData = $this->getInvoiceData($currentInvoiceWeek['result']);

            $result[$index] = array_merge(
                [
                    "date" => $time,
                ],
                $invoiceData
            );
        }

        return $this->addPercentageForInvoice($result);
    }

    /**
     * @param $invoiceData
     * @return array
     */
    private function getInvoiceData($invoiceData)
    {
        if ($invoiceData === 0) {
            return $this->generateInvoiceStatistic(0, 0);
        }

        $totalInvoice = count($invoiceData);

        $amount = array_reduce(
            $invoiceData,
            function ($carry, $invoice) {
                return ($carry + $invoice['amount']);
            },
            0
        );

        return $this->generateInvoiceStatistic($totalInvoice, $amount);
    }

    /**
     * @param $total
     * @param $amount
     * @return array
     */
    private function generateInvoiceStatistic($total, $amount)
    {
        return [
            "totalInvoice" => $total,
            "totalInvoiceAmount" => $amount,
        ];
    }

    /**
     * @param $invoiceData
     * @return mixed
     */
    private function addPercentageForInvoice($invoiceData)
    {
        for ($index = 0; $index < count($invoiceData); $index++) {
            if ($index !== 0) {
                $invoiceData[$index]['percent'] = round((($invoiceData[$index]["totalInvoiceAmount"] - $invoiceData[$index - 1]["totalInvoiceAmount"]) / 100), 1) . "%";
            } else {
                $invoiceData[$index]['percent'] = round(100, 1) . "%";
            }
        }

        return $invoiceData;
    }
}
