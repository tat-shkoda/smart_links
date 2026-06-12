<?php

namespace App\Infrastructure\Http;

use App\Domain\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ContextBuilderMiddleware implements MiddlewareInterface
{

    public function __construct(
        private iterable $providers,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $context = new Context();

        foreach ($this->providers as $provider) {
            $provider->provide($context, $request);
        }

        $request = $request->withAttribute('context', $context);

        return $handler->handle($request);
    }
}
