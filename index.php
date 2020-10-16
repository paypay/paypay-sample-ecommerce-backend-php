<?php

require_once('./vendor/autoload.php');
use PayPay\OpenPaymentAPI\Client;

$client = new Client(
    [
        'API_KEY' => getenv('API_KEY'),
        'API_SECRET' => getenv('API_SECRET'),
        'MERCHANT_ID' => getenv('MERCHID')
    ],
    false //set to true for production mode
);
require_once "./src/index.php";
$service = new CakeshopService();
$service->load_route($client);