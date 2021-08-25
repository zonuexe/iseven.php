<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use zonuexe\isEvenApi\Client;

/**
 * キャッシュされたAPIクライアントを返す
 */
function get_client(): Client
{
    static $client;

    if ($client === null) {
        $client = new Client(
            HttpClientDiscovery::find(),
            MessageFactoryDiscovery::find()
        );
    }

    return $client;
}

function is_even(int $n): bool
{
    return get_client()->request($n)->isEven();
}

function is_odd(int $n): bool
{
    return !get_client()->is_even($n);
}
