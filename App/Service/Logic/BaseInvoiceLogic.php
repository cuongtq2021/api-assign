<?php
namespace App\Service\Logic;

abstract class BaseInvoiceLogic implements LogicInterface {
    private $invoiceList;

    /**
     * @return mixed
     */
    public function getInvoiceList()
    {
        return $this->invoiceList;
    }

    /**
     * @param mixed $orderList
     */
    public function setInvoiceList($orderList): void
    {
        $this->invoiceList = $orderList;
    }
}
