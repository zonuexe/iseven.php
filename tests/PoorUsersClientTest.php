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
final class PoorUsersClientTest extends AbstractClientTest
{
    public function getHttpClient(): HttpClient
    {
        return new class($this->getKnownNumbers()) extends MappedResponseHttpClient {
            public function buildJsonData(string $key): array
            {
                /** @var bool $is_even */
                $is_even = $this->map[$key];

                return [
                    'iseven' => $is_even,
                    'ad' => 'Python is Awesome',
                ];
            }
        };
    }

    public function assertDumpedAd(int $input, string $output): void
    {
        $msg = '[Ad] Python is Awesome';
        $expected = implode("\n", [$msg, $msg, '']);

        $this->assertSame($expected, $output);
    }
}
