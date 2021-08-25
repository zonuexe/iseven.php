<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;

use zonuexe\isEvenApi\Client;

/**
 * Return cached isEvenApi Client (singleton)
 */
function get_client(): Client
{
    static $client;

    if ($client === null) {
        $client = new Client(
            HttpClientDiscovery::find(),
            Psr17FactoryDiscovery::findRequestFactory()
        );
    }

    return $client;
}

/**
 * Is $n even?
 *
 * @param resource $stdout
 */
function is_even(int $n, $stdout = STDERR): bool
{
    return get_client()->request($n)->isEven($stdout);
}

/**
 * Is $n odd?
 *
 * @param resource $stdout
 */
function is_odd(int $n, $stdout = STDERR): bool
{
    return !is_even($n, $stdout);
}
