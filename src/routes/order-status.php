<?php

$resp =  $client->payment->getPaymentDetails($urlparam);
header('Content-Type: application/json');
print_r(json_encode($resp));