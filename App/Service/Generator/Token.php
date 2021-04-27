<?php

namespace App\Service\Generator;

use App\Service\DataProvider\FixablyApi;

class Token
{

    /**
     * Token constructor.
     */
    public function __construct()
    {
        $this->api = new FixablyApi();
    }

    /**
     * @return string
     */
    public function getTokenFromAPI(): string
    {
        return $this->api->getToken();
    }
}
