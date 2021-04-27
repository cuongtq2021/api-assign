<?php
namespace App\Service\Generator;

use App\Entity\ReportData;
use App\Service\DataProvider\FixablyApi;
use \App\Service\Generator\ReportInterface;
use App\Service\Logic\BaseOrderLogic;

abstract class BaseOrderReport implements ReportInterface {
    private BaseOrderLogic $logic;
    private FixablyApi $api;

    /**
     * BaseOrderReport constructor.
     * @param BaseOrderLogic $logic
     * @param FixablyApi $api
     */
    public function __construct(
        BaseOrderLogic $logic,
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
