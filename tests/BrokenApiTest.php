<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Message\RequestInterface as HttpRequest;
use zonuexe\isEvenApi\HttpClient\MappedResponseHttpClient;
use function implode;

/**
 * @extends AbstractClientTest<MappedResponseHttpClient>
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BrokenApiTest extends AbstractClientTest
{
    public function getHttpClient(): HttpClient
    {
        return new class([]) extends MappedResponseHttpClient {
            public function buildJsonData(string $key): array
            {
                return [];
            }
        };
    }

    public function assertDumpedAd(int $input, string $output): void
    {
        $msg = '[Ad] PHP is a popular general-purpose scripting language that is especially suited to web development.  Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.';
        $expected = implode("\n", [$msg, $msg, '']);

        $this->assertSame($expected, $output);
    }
}
