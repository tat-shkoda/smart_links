<?php

namespace App\Application\Dsl;

interface DslFunctionInterface
{

    public function getName(): string;
    public function evaluate(array $context, mixed ...$args): mixed;
}
