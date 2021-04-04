<?php

declare(strict_types = 1);

namespace App\Calculator\Domain\Adder;

final class Adder implements AdderInterface
{
    public function add(int ...$numbers): int
    {
        return array_sum($numbers);
    }
}
