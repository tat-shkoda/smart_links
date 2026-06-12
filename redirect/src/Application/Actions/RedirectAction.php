<?php

namespace App\Application\Actions;

use App\Application\RedirectService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RedirectAction
{

    public function __construct(
        private RedirectService $redirectService,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $code = (string) $request->getAttribute('code');
        $context = $request->getAttribute('context');

        $target = $this->redirectService->resolve($code, $context);

        return $response
            ->withStatus(302)
            ->withHeader('Location', $target);
    }
}
