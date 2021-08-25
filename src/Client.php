<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface as RequestFactory;
use Psr\Http\Message\RequestInterface;
use function json_decode;
use function range;

class Client
{
    /** @var HttpClient */
    private $http_client;
    /** @var RequestFactory */
    private $request_facory;

    private const DEFAULT_AD
        = 'PHP is a popular general-purpose scripting language that is especially suited to web development.  '
        . 'Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.';

    public function __construct(HttpClient $http_client, RequestFactory $request_facory)
    {
        $this->http_client = $http_client;
        $this->request_facory = $request_facory;
    }

    public function request(int $n)
    {
        try {
            $response = $this->http_client->sendRequest($this->createRequest($n));
            $data = json_decode((string)$response->getBody(), true);
        } catch (NetworkExceptionInterface $e) {
            $data = ['iseven' => $this->localIsEven($n), 'ad' => self::DEFAULT_AD];
        }

        return new Entity($n, $data['iseven'], $data['ad'] ?? null);
    }

    public function createRequest(int $n)
    {
        return $this->request_facory->createRequest('GET', $this->buildUri($n));
    }

    public function buildUri(int $n): string
    {
        return "https://api.isevenapi.xyz/api/iseven/{$n}/";
    }

    /**
     * Fallback implementation in case a connection is lost.
     */
    function localIsEven(int $n): bool
    {
        $bit = 0b0;
        foreach (range(1, $n) as $_) {
            $bit ^= 0b1;
        }

        return $bit === 0b0;
    }
}
