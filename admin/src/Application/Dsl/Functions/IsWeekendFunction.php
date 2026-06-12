<?php

namespace App\Application\Dsl\Functions;

use App\Application\Dsl\DslFunctionInterface;

final class IsWeekendFunction implements DslFunctionInterface
{

    public function getName(): string
    {
        return 'is_weekend';
    }

    public function evaluate(array $context, mixed ...$args): bool
    {
        $weekday = (int) ($context['weekday'] ?? 0);

        return in_array($weekday, [6, 7], true);
    }
}
