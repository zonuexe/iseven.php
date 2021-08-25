<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Message\RequestInterface as HttpRequest;
use zonuexe\isEvenApi\HttpClient\OfflineHttpClient;
use function implode;

/**
 * @extends AbstractClientTest<OfflineHttpClient>
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class OfflineClientTest extends AbstractClientTest
{
    public function getHttpClient(): HttpClient
    {
        return new OfflineHttpClient();
    }

    public function assertDumpedAd(int $input, string $output): void
    {
        $msg = '[Ad] PHP is a popular general-purpose scripting language that is especially suited to web development.  Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.';
        $expected = implode("\n", [$msg, $msg, '']);

        $this->assertSame($expected, $output);
    }
}
