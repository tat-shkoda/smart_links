<?php

namespace App\Domain;

use Psr\Http\Message\ServerRequestInterface;

interface ContextProviderInterface
{

    public function provide(ContextInterface $context, ServerRequestInterface $request): void;
}
