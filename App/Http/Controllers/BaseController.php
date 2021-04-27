<?php

namespace App\Http\Controllers;

class BaseController
{
    /**
     * @param $result
     */
    public function jsonResponse($result)
    {
        echo json_encode($result->getRowList(), JSON_PRETTY_PRINT);
    }

}
