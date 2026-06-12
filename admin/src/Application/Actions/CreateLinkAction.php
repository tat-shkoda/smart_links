<?php

namespace App\Application\Actions;

use App\Application\Dto\CreateLinkInput;
use App\Application\Services\CreateLinkServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateLinkAction
{

    public function __construct(
        private CreateLinkServiceInterface $creator,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        $payload = $request->getParsedBody();

        $link = $this->creator->create(new CreateLinkInput(
            $payload['code'],
            $payload['default'],
            $payload['rules'],
        ));

        $response->getBody()->write(json_encode([
            'status' => 'ok',
            'data' => [
                'code' => $link->code,
            ],
        ]));

        return $response;
    }
}
