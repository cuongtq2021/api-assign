<?php

namespace App\Http\Controllers;

use App\Service\DataProvider\FixablyApi;
use App\Service\Generator\ListAssignedIphoneOrder;
use App\Service\Generator\ListOfInvoice;
use App\Service\Generator\ListOrderByStatus;
use App\Service\Generator\Token;
use App\Service\Logic\AssignedIphoneOrderListLogic;
use App\Service\Logic\InvoicesLogic;
use App\Service\Logic\OrderStatisticLogic;

/**
 * Class PostController
 * @package App\Http\Controllers
 */
class PostController extends BaseController
{

    public function __construct()
    {
        $this->api = new FixablyApi();
        $this->orderStatisticLogic = new OrderStatisticLogic();
        $this->assignedIphoneOrderListLogic = new AssignedIphoneOrderListLogic();
        $this->invoicesLogic = new InvoicesLogic();
    }

    public function index()
    {
        echo "Fixable assignment";
    }

    public function token()
    {
        $token = $this->getToken();
        echo $this->jsonResponse($token);
    }

    public function getToken()
    {
        $tokenGenerate = new Token();
        $token = $tokenGenerate->getTokenFromAPI();
        return $token;
    }

    public function orders()
    {
        $orderGenerator = new ListOrderByStatus($this->api, $this->orderStatisticLogic);
        return $this->jsonResponse($orderGenerator->getReportData());
    }

    public function assignedIphoneOrderList()
    {
        $orderGenerator = new ListAssignedIphoneOrder($this->api, $this->assignedIphoneOrderListLogic);
        return $this->jsonResponse($orderGenerator->getReportData()->getRowList());
    }

    public function invoices () {
        $invoiceGenerator = new ListOfInvoice($this->api, $this->invoicesLogic);
        return $this->jsonResponse($invoiceGenerator->getReportData()->getRowList());
    }

    public function createOrder(){
        return $this->jsonResponse($this->api->createOrder());
    }
}
