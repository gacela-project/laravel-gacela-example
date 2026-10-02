<?php

declare(strict_types=1);

namespace Src\Product\Application;

/**
 * Request state: the Factory hands out one instance, which a long-running
 * worker must drop between requests.
 */
final class CreatedProducts
{
    /** @var list<string> */
    private array $names = [];

    public function add(string $name): void
    {
        $this->names[] = $name;
    }

    /**
     * @return list<string>
     */
    public function names(): array
    {
        return $this->names;
    }
}
