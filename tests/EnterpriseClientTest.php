<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Message\ResponseInterface as HttpResponse;
use Psr\Http\Message\UriInterface;
use zonuexe\isEvenApi\HttpClient\MappedResponseHttpClient;

/**
 * @extends AbstractClientTest<MappedResponseHttpClient>
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class EnterpriseClientTest extends AbstractClientTest
{
    public function getHttpClient(): HttpClient
    {
        return new class($this->getKnownNumbers()) extends MappedResponseHttpClient {
            public function buildJsonData(string $key): array
            {
                $is_even = $this->map[$key];

                return [
                    'iseven' => $is_even,
                ];
            }
        };
    }

    public function assertDumpedAd(int $input, string $output): void
    {
        $this->assertSame('', $output);
    }
}
