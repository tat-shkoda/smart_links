<?php

namespace App\Domain;

interface ContextInterface
{

    public function get(string $key): mixed;
    public function set(string $key, mixed $value): void;
    public function toArray(): array;
}
