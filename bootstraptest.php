<?php
require_once('./loadDependencies.php');
use PayPay\OpenPaymentAPI\Client;

$client = new Client(
    [
        'API_KEY' => getenv('API_KEY'),
        'API_SECRET' => getenv('API_SECRET'),
        'MERCHANT_ID' => getenv('MERCHID')
    ],
    'test' //set to true for production mode
);
require_once "./src/index.php";