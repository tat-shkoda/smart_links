<?php

namespace App\Application;

use App\Domain\ContextInterface;
use App\Domain\LinkReaderInterface;
use App\Domain\Rule;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class RedirectService
{

    public function __construct(
        private LinkReaderInterface $reader,
        private ExpressionLanguage $el,
    ) {}

    public function resolve(string $code, ContextInterface $context): string
    {
        $link = $this->reader->read($code);

        $rules = $link->rules;

        usort($rules, fn(Rule $a, Rule $b) => $b->priority <=> $a->priority);

        foreach ($rules as $rule) {
            if (!$this->el->evaluate($rule->expression, $context->toArray())) {
                continue;
            }

            return $rule->target;
        }

        return $link->default;
    }
}
