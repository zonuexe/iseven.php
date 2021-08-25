<?php

declare(strict_types=1);

use zonuexe\isEvenApi;
use Nyholm\Psr7\Factory\Psr17Factory;

require __DIR__ . '/vendor/autoload.php';

$http_factory = new Psr17Factory();
$http_client = new Buzz\Client\Curl($http_factory);

$client = new isEvenApi\Client($http_client, $http_factory);

var_dump($client->request(1)->isEven());
var_dump($client->request(2)->isEven());
