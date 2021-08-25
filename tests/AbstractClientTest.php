<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use Generator;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Client\ClientInterface as HttpClient;
use function fopen;
use function rewind;
use function Safe\stream_get_contents;

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

    abstract public function assertDumpedAd(int $input, string $output): void;

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

        $this->assertSame($input, $actual->getNumber());
        $this->assertSame($expected_is_even, $actual->isEven($buffer));
        $this->assertSame(!$expected_is_even, $actual->isOdd($buffer));

        rewind($buffer);
        $this->assertDumpedAd($input, stream_get_contents($buffer));
    }

    public function test_buildUri(): void
    {
        $actual = $this->subject->buildUri(1234);
        $this->assertSame('https://api.isevenapi.xyz/api/iseven/1234/', $actual);
    }

    public function test_createRequest(): void
    {
        $actual = $this->subject->createRequest(1234);
        $this->assertSame('https://api.isevenapi.xyz/api/iseven/1234/', (string)$actual->getUri());
    }

    /**
     * @return Generator<int,array{0:int,1:bool}>
     */
    public function numbersProvider(): Generator
    {
        foreach ($this->getKnownNumbers() as $number => $is_even) {
            yield $number => [$number, $is_even];
        }
    }

    /**
     * @return array<int,bool>
     */
    public function getKnownNumbers(): array
    {
        return [
            0 => true,
            1 => false,
            2 => true,
            3 => false,
            4 => true,
            5 => false,
            6 => true,
            7 => false,
            8 => true,
            9 => false,
            10 => true,
        ];
    }
}
