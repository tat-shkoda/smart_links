<?php

namespace App\Domain;

class Context implements ContextInterface
{

    public function __construct(
        private array $map = [],
    ) {}

    public function get(string $key): mixed
    {
        return $this->map[$key];
    }

    public function set(string $key, mixed $value): void
    {
        $this->map[$key] = $value;
    }

    public function toArray(): array
    {
        return $this->map;
    }
}
