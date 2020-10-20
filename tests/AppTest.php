<?php

use PayPay\OpenPaymentAPI\Client;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testMain()
    {
        $client = new Client(
            [
                'API_KEY' => '',
                'API_SECRET' => '',
                'MERCHANT_ID' => ''
            ],
            'test' //set to true for production mode
        );
        $route = new Routes($client, '');
        $this->assertNotEmpty($route->cakes());
        $orderJson  = <<<JSON
        {
            "orderItems": [{
                "name": "Moon cake",
                "category": "pastries",
                "quantity": 1,
                "productId": "67678",
                "unitPrice": {
                    "amount": 11,
                    "currency": "JPY"
                }
            }],
            "amount": {
                "amount": 11,
                "currency": "JPY"
            }
        }
JSON;
        $this->assertNotEmpty($route->createQr($orderJson));
        $this->assertNotEmpty($route->orderStatus("RANDOMORDER"));
    }
}
