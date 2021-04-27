<?php
namespace App\Service\Generator;

use App\Entity\ReportData;
use App\Service\DataProvider\FixablyApi;
use App\Service\Logic\InvoicesLogic;

abstract class BaseInvoiceReport implements ReportInterface {
    private InvoicesLogic $logic;
    private FixablyApi $api;

    /**
     * BaseInvoiceReport constructor.
     * @param BaseInvoiceReport $logic
     * @param FixablyApi $api
     */
    public function __construct(
        BaseInvoiceReport $logic,
        FixablyApi $api
    )
    {
        $this->logic = $logic;
        $this->api = $api;
    }


    /**
     * @return ReportData
     */
    public function generateReport(): ReportData
    {
    }
}
