<?php

namespace App\Application\Dto;

use App\Domain\Rule;

class CreateLinkInput
{

    /**
     * @param string $code
     * @param string $default
     * @param Rule[] $rules
     */
    public function __construct(
        public readonly string $code,
        public readonly string $default,
        public readonly array $rules,
    ) {}
}
