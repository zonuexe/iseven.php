<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface as RequestFactory;
use Psr\Http\Message\RequestInterface;
use function is_array;
use function is_bool;
use function is_string;
use function json_decode;
use function range;

class Client
{
    private HttpClient $http_client;
    private RequestFactory $request_facory;

    private const DEFAULT_AD
        = 'PHP is a popular general-purpose scripting language that is especially suited to web development.  '
        . 'Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.';

    public function __construct(HttpClient $http_client, RequestFactory $request_facory)
    {
        $this->http_client = $http_client;
        $this->request_facory = $request_facory;
    }

    public function request(int $n): Entity
    {
        try {
            $response = $this->http_client->sendRequest($this->createRequest($n));
            $data = json_decode((string)$response->getBody(), true);
            $this->validateResponse($data);
            /** @phan-suppress-next-line PhanUnusedVariableCaughtException */
        } catch (NetworkExceptionInterface | ResponseValidationException $e) {
            $data = ['iseven' => $this->localIsEven($n), 'ad' => self::DEFAULT_AD];
        }

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        assert($data !== null);

        /** @phan-suppress-next-line PhanPartialTypeMismatchArgument */
        return new Entity($n, $data['iseven'], $data['ad'] ?? null);
    }

    public function createRequest(int $n): RequestInterface
    {
        return $this->request_facory->createRequest('GET', $this->buildUri($n));
    }

    public function buildUri(int $n): string
    {
        return "https://api.isevenapi.xyz/api/iseven/{$n}/";
    }

    /**
     * Fallback implementation in case the connection is lost, or the
     * response is malformed for some reason such as the end of service.
     */
    private function localIsEven(int $n): bool
    {
        $bit = 0b0;
        /** @infection-ignore-all */
        foreach (range(1, $n) as $_) {
            $bit ^= 0b1;
        }

        return $bit === 0b0;
    }

    /**
     * @param mixed $data
     * @psalm-assert array{iseven: bool, ad?: string} $data
     * @throws ResponseValidationException
     */
    private function validateResponse($data): void
    {
        if (
            !is_array($data)
            || (!isset($data['iseven']) || !is_bool($data['iseven']))
            || (isset($data['ad']) && !is_string($data['ad']))
        ) {
            throw new ResponseValidationException();
        }
    }
}
