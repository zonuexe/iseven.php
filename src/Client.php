<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Message\RequestFactoryInterface as RequestFactory;
use Psr\Http\Message\RequestInterface;
use function json_decode;

class Client
{
    /** @var HttpClient */
    private $http_client;
    /** @var RequestFactory */
    private $request_facory;

    public function __construct(HttpClient $http_client, RequestFactory $request_facory)
    {
        $this->http_client = $http_client;
        $this->request_facory = $request_facory;
    }

    public function request(int $n)
    {
        $response = $this->http_client->sendRequest($this->createRequest($n));

        $data = json_decode((string)$response->getBody(), true);

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
}
