<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Generator;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Client\ClientInterface as HttpClient;
use function fopen;

/**
 * @template T of HttpClient
 */
abstract class AbstractClientTest extends TestCase
{
    protected Client $subject;

    /**
     * @psalm-return T
     */
    abstract public function getHttpClient(): HttpClient;

    final public function setUp(): void
    {
        $this->subject = new Client(
            $this->getHttpClient(),
            Psr17FactoryDiscovery::findRequestFactory()
        );
    }

    /**
     * @dataProvider numbersProvider
     */
    public function test(int $input, bool $expected_is_even): void
    {
        $actual = $this->subject->request($input);
        $buffer = fopen('php://memory', 'rw');

        assert($buffer !== false);

        $this->assertSame($expected_is_even, $actual->isEven($buffer));
        $this->assertSame(!$expected_is_even, $actual->isOdd($buffer));
    }

    /**
     * @return Generator<int,array{0:int,1:bool}>
     */
    public function numbersProvider(): Generator
    {
        yield 0 => [0, true];
        yield 1 => [1, false];
        yield 2 => [2, true];
        yield 3 => [3, false];
        yield 4 => [4, true];
        yield 5 => [5, false];
        yield 6 => [6, true];
        yield 7 => [7, false];
        yield 8 => [8, true];
        yield 9 => [9, false];
        yield 10 => [10, true];
    }
}
