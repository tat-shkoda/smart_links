<?php

namespace App\Application\Validation;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

class RuleValidator
{

    public function __construct(
        private ExpressionLanguage $expressionLanguage,
        private array $allowedVars,
    ) {}

    public function validate(string $expression): void
    {
        try {
            $this->expressionLanguage->lint($expression, $this->allowedVars);
        } catch (SyntaxError $e) {
            throw new InvalidExpression("Invalid expression: {$e->getMessage()}");
        }
    }
}
