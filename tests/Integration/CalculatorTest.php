<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Calculator\Facade;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    private Facade $facade;

    public function setUp(): void
    {
        $this->facade = new Facade();
    }

    public function testEmptyFacade(): void
    {
        self::assertSame(0, $this->facade->add());
    }

    public function testSingleNumber(): void
    {
        self::assertSame(1, $this->facade->add(1));
    }

    public function testMultipleNumbers(): void
    {
        self::assertSame(6, $this->facade->add(1, 2, 3));
    }

    public function testNegativeNumbers(): void
    {
        self::assertSame(-5, $this->facade->add(-2, -3));
    }
}
