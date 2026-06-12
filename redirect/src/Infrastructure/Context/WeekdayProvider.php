<?php

namespace App\Infrastructure\Context;

use App\Domain\ContextInterface;
use App\Domain\ContextProviderInterface;
use Override;
use Psr\Http\Message\ServerRequestInterface;

class WeekdayProvider implements ContextProviderInterface
{

    #[Override]
    public function provide(ContextInterface $context, ServerRequestInterface $request): void
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $context->set('weekday', (int) $now->format('N'));
    }
}
