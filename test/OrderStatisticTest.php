<?php

namespace App\Service\Logic;

use PHPUnit\Framework\TestCase;
use App\Service\Logic\AssignedIphoneOrderListLogic;

class OrderStatisticTest extends TestCase
{
    public function setup(): void
    {
        parent::setUp();
        $this->assignedIphoneOrderList = $this->getMockBuilder('\App\Service\Logic\AssignedIphoneOrderListLogic')->disableOriginalConstructor(
        )->setMethods(null)->getMock();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGenerateOrderData($expected, $orderList)
    {
        //Act
        $actual = $this->assignedIphoneOrderList->filterOrderData($orderList);
        //Assert
        $this->assertSame($expected, $actual);
    }

    public function dataProvider()
    {
        return [
            "it should return empty array which not assigned to technician" => [
                [],
                [
                    [
                        "id" => 9243,
                        "deviceType"=> "Phone",
                        "deviceManufacturer"=> "Apple",
                        "deviceBrand"=> "iPhone X",
                        "technician"=> "Pasi",
                        "status"=> 1,
                        "created"=> "2020-10-01 10:05:57"
                    ],
                    [
                        "id"=> 9244,
                        "deviceType"=> "Laptop",
                        "deviceManufacturer"=> "Apple",
                        "deviceBrand"=> "MacBook Pro",
                        "technician"=> null,
                        "status"=> 4,
                        "created"=> "2020-10-01 10:10:42"
                    ],
                    [
                        "id"=> 9245,
                        "deviceType"=> "Tablet",
                        "deviceManufacturer"=> "Apple",
                        "deviceBrand"=> "iPad Air",
                        "technician"=> "Anniina",
                        "status"=> 2,
                        "created"=> "2020-10-01 10:22:56"
                    ]
                ]
            ],
            "it should return the iphone order which is assigned to technician" => [
                [
                    [
                        "id" => 9243,
                        "deviceType"=> "Phone",
                        "deviceManufacturer"=> "Apple",
                        "deviceBrand"=> "iPhone",
                        "technician"=> "Pasi",
                        "status"=> 3,
                        "created"=> "2020-10-01 10:05:57"
                    ]
                ],
                [
                    [
                        "id" => 9243,
                        "deviceType"=> "Phone",
                        "deviceManufacturer"=> "Apple",
                        "deviceBrand"=> "iPhone",
                        "technician"=> "Pasi",
                        "status"=> 3,
                        "created"=> "2020-10-01 10:05:57"
                    ],
                    [
                        "id"=> 9244,
                        "deviceType"=> "Laptop",
                        "deviceManufacturer"=> "Apple",
                        "deviceBrand"=> "MacBook Pro",
                        "technician"=> null,
                        "status"=> 1,
                        "created"=> "2020-10-01 10:10:42"
                    ],
                    [
                        "id"=> 9245,
                        "deviceType"=> "Tablet",
                        "deviceManufacturer"=> "Apple",
                        "deviceBrand"=> "iPad Air",
                        "technician"=> "Anniina",
                        "status"=> 2,
                        "created"=> "2020-10-01 10:22:56"
                    ]
                ]
            ]
        ];
    }
}
