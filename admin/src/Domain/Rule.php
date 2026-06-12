<?php

namespace App\Domain;

class Rule
{

    public function __construct(
        public readonly string $expression,
        public readonly string $target,
        public readonly int $priority = 0,
    ) {}
}
