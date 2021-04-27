<?php
namespace App\Service\Logic;

abstract class BaseOrderLogic implements LogicInterface {
    private $orderList;
    private $orderStatus;

    /**
     * @return mixed
     */
    public function getOrderList()
    {
        return $this->orderList;
    }

    /**
     * @param mixed $orderList
     */
    public function setOrderList($orderList): void
    {
        $this->orderList = $orderList;
    }

    /**
     * @return mixed
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @param mixed $orderStatus
     */
    public function setOrderStatus($orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }


}
