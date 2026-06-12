<?php

namespace App\Domain;

final class Link
{

    public function __construct(
        public readonly string $code,
        public readonly string $default,
        public readonly array $rules,
    ) {}
}
