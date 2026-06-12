<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Services\GetLinkByCodeServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetLinkByCodeAction
{

    public function __construct(
        private GetLinkByCodeServiceInterface $getter,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $code = (string) $request->getAttribute('code');

        $link = $this->getter->get($code);

        $response->getBody()->write(json_encode([
            'status' => 'ok',
            'data' => [
                'code' => $link->code,
                'default' => $link->default,
                'rules' => $link->rules,
            ],
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
