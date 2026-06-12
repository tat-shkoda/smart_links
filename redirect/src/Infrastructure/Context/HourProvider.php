<?php

namespace App\Infrastructure\Context;

use App\Domain\ContextInterface;
use App\Domain\ContextProviderInterface;
use Psr\Http\Message\ServerRequestInterface;
use Override;

class HourProvider implements ContextProviderInterface
{

    #[Override]
    public function provide(ContextInterface $context, ServerRequestInterface $request): void
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $context->set('hour', (int) $now->format('G'));
    }
}
