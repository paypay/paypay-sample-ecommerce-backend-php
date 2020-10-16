<?php
require_once './vendor/autoload.php';

use PayPay\OpenPaymentAPI\Client;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;
use PayPay\OpenPaymentAPI\Models\OrderItem;
$appConfig = [
    'hostname' => "localhost:5000"
];
$routes = [
    '/cakes',
    '/create-qr',
    '/order-status'
];
class CakeshopService
{
    function site_url($echome = false)
    {
        global $appConfig;
        $host = $appConfig['hostname'];
        $host = htmlspecialchars($host);
        if ($echome) {
            print_r($host . '/');
        }
        return ('http://' . $host . '/');
    }

    function url_path($getArr = false)
    {
        $k = htmlspecialchars(filter_input(INPUT_SERVER, 'REQUEST_URI'));
        $k_arr = explode('/', $k);
        return $getArr ? $k_arr : $k;
    }

    function load_route($client)
    {
        header('Content-Type: application/json');

        global $routes;
        $urlparts = $this->url_path(true);
        $len = count($urlparts);
        $routeIndex = array_search('/' . ($len > 1 ? $urlparts[1] : ''), $routes);
        if ($routeIndex !== FALSE) {
            $routePath = $routes[$routeIndex];
            $intended_file = "./src/routes" . $routePath . ".php";
            $urlparam = '';
            if (isset($urlparts[2]) && $urlparts[2] && $urlparts[2] != '') {
                $urlparam = $urlparts[2];
            }
            
            $route = new Routes($client, $urlparam);
            switch ($routePath) {
                case $routes[0]:
                    $route->cakes();
                    break;
                case $routes[1]:
                    $json = file_get_contents('php://input');
                    $route->createQr($json);
                    break;
                case $routes[2]:
                    $route->orderStatus($urlparam);
                    break;
                

                default:
                    # code...
                    break;
            }
            
        } else {
            print_r('No route found!');
        }
    }
}

class Routes
{

    public function __construct(\PayPay\OpenPaymentAPI\Client $client, string $urlparam)
    {
        $this->client = $client;
        $this->urlparam = $urlparam;
    }
    function cakes()
    {
        $cakes = [
            [
                "title" => "cake_shop.mississippi",
                "id" => 1,
                "price" => 120,
                "image" => "darkforest.png"
            ], [
                "title" => "cake_shop.red_velvet",
                "id" => 2,
                "price" => 190,
                "image" => "redvelvet.png"
            ], [
                "title" => "cake_shop.dark_forest",
                "id" => 3,
                "price" => 100,
                "image" => "darkforestcake.png"
            ], [
                "title" => "cake_shop.rainbow",
                "id" => 4,
                "price" => 200,
                "image" => 'rainbow.png'
            ], [
                "title" => "cake_shop.lemon",
                "id" => 5,
                "price" => 80,
                "image" => 'lemon.png'
            ], [
                "title" => "cake_shop.pineapple",
                "id" => 6,
                "price" => 110,
                "image" => 'pineapple.png'
            ], [
                "title" => "cake_shop.banana",
                "id" => 7,
                "price" => 90,
                "image" => 'banana.png'
            ], [
                "title" => "cake_shop.carrot",
                "id" => 8,
                "price" => 165,
                "image" => 'carrot.png'
            ], [
                "title" => "cake_shop.choco",
                "id" => 9,
                "price" => 77,
                "image" => 'choco.png'
            ], [
                "title" => "cake_shop.chocochip",
                "id" => 10,
                "price" => 130,
                "image" => 'chocochip.png'
            ], [
                "title" => "cake_shop.orange",
                "id" => 11,
                "price" => 140,
                "image" => 'orange.png'
            ], [
                "title" => "cake_shop.butterscotch",
                "id" => 12,
                "price" => 155,
                "image" => 'butterscotch.png'
            ],
        ];
        print_r(json_encode($cakes));
        return $cakes;
    }
    function createQr($json)
    {
        
        $req = json_decode($json, true);

        $payload = new CreateQrCodePayload();
        $payload->setMerchantPaymentId(uniqid('MUNECAKE_'));
        $payload->setCodeType();
        $payload->setAmount($req['amount']);
        $orderItems = [];
        foreach ($req['orderItems'] as $key => $item) {
            $incomingItem = (new OrderItem())->setName($item['name'])->setQuantity($item['quantity'])->setUnitPrice($item['unitPrice']);
            if (isset($item['productId'])) {
                $incomingItem->setProductId("${item['productId']}");
            }
            if (isset($item['category'])) {
                $incomingItem->setCategory($item['category']);
            }
            $orderItems[] = $incomingItem;
        }
        $mpid = $payload->getMerchantPaymentId();
        $payload->setOrderItems($orderItems)->setRequestedAt();
        $payload->setRedirectType('WEB_LINK')->setRedirectUrl("http://merchant.com" . "/orderpayment/$mpid");
        try {
            $resp = $this->client->code->createQRCode($payload);
            $output =json_encode($resp);
            print_r($output);
            return $output;
        } catch (Exception $e) {
            http_response_code($e->getCode());
            $response = [
                "resultInfo" => [
                    "code" => "string",
                    "message" => $e->getMessage(),
                    "codeId" => "string"
                ]
            ];
        }
    }
    public function orderStatus($merchantPaymentId)
    {
        $resp =  $this->client->payment->getPaymentDetails($merchantPaymentId);
        print_r(json_encode($resp));
        return $resp;
    }
}


