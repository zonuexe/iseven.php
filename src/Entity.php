<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

use function fwrite;

/**
 * @immutable
 */
class Entity
{
    private int $n;
    private bool $is_even;
    private ?string $ad;

    public function __construct(int $n, bool $is_even, ?string $ad)
    {
        $this->n = $n;
        $this->is_even = $is_even;
        $this->ad = $ad;
    }

    /**
     * @param resource $stdout
     */
    public function isEven($stdout): bool
    {
        if ($this->ad !== null) {
            fwrite($stdout, "[Ad] {$this->ad}\n");
        }

        return $this->is_even;
    }

    /**
     * @param resource $stdout
     */
    public function isOdd($stdout): bool
    {
        return !$this->isEven($stdout);
    }
}
