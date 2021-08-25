<?php

declare(strict_types=1);

use zonuexe\isEvenApi;
use Nyholm\Psr7\Factory\Psr17Factory;

require __DIR__ . '/vendor/autoload.php';

$http_factory = new Psr17Factory();
$http_client = new Buzz\Client\Curl($http_factory);

$client = new isEvenApi\Client($http_client, $http_factory);

// var_dump($client->request(1)->isEven()); // => false
// var_dump($client->request(2)->isEven()); // => true

var_dump(zonuexe\isEvenApi\is_even(1));

function is_even(int $n): bool
{
    return json_decode(
        file_get_contents("https://api.isevenapi.xyz/api/iseven/{$n}/")
    )->iseven;
};
