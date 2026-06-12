<?php

namespace App\Application\Dsl;

final class DslFunctionRegistry
{

    private array $functions = [];

    public function __construct(iterable $functions)
    {
        foreach ($functions as $function) {
            $this->functions[$function->getName()] = $function;
        }
    }

    public function all():  array
    {
        return $this->functions;
    }

    public function names(): array
    {
        return array_keys($this->functions);
    }
}
