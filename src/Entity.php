<?php

declare(strict_types=1);

namespace zonuexe\isEvenApi;

class Entity
{
    /** @var int $n */
    private $n;
    /** @var bool $is_even */
    private $is_even;
    /** @var ?string $ad */
    private $ad;

    public function __construct(int $n, bool $is_even, ?string $ad)
    {
        $this->n = $n;
        $this->is_even = $is_even;
        $this->ad = $ad;
    }

    public function isEven(): bool
    {
        return $this->is_even;
    }

    public function isOdd(): bool
    {
        return !$this->isEven();
    }
}
