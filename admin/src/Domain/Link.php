<?php

namespace App\Domain;

final class Link
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

    public static function fromArray(array $data): Link
    {
        return new Link(
            code: $data['code'],
            default: $data['default'],
            rules: $data['rules'],
        );
    }
}
