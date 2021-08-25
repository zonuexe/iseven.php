<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi\HttpClient;

use Http\Client\Exception\NetworkException;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface as HttpRequest;
use Psr\Http\Message\ResponseInterface as HttpResponse;
use Psr\Http\Message\UriInterface;
use function preg_match;
use function Safe\json_encode;

abstract class MappedResponseHttpClient implements HttpClient
{
    /** @var array<mixed> */
    protected array $map;

    public function parseUri(UriInterface $uri): string
    {
        preg_match('@/(?<id>\d+)/\z@', (string)$uri, $matches);

        return $matches['id'];
    }

    /** @return array<mixed> */
    abstract public function buildJsonData(string $key): array;

    /**
     * @param array<mixed> $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function sendRequest(HttpRequest $request): HttpResponse
    {
        $response_factory = Psr17FactoryDiscovery::findResponseFactory();
        $stream_factory = Psr17FactoryDiscovery::findStreamFactory();

        $key = $this->parseUri($request->getUri());

        return $response_factory->createResponse(200)->withBody(
            $stream_factory->createStream(json_encode(
                $this->buildJsonData($key)
            ))
        );
    }
}
