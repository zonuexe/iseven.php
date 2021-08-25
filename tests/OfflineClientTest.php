<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Psr\Http\Client\ClientInterface as HttpClient;
use zonuexe\isEvenApi\HttpClient\OfflineHttpClient;

/**
 * @extends AbstractClientTest<OfflineHttpClient>
 */
final class OfflineClientTest extends AbstractClientTest
{
    public function getHttpClient(): HttpClient
    {
        return new OfflineHttpClient();
    }
}
