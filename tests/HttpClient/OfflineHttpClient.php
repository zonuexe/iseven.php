<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi\HttpClient;

use Http\Client\Exception\NetworkException;
use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\ResponseInterface as HttpResponse;
use Psr\Http\Message\RequestInterface as HttpRequest;

class OfflineHttpClient implements HttpClient
{
    public function sendRequest(HttpRequest $request): HttpResponse
    {
        throw new NetworkException('Lost connection.', $request);
    }
}
