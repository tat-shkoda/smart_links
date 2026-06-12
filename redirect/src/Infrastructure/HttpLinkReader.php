<?php

namespace App\Infrastructure;

use App\Domain\Link;
use App\Domain\LinkReaderInterface;
use App\Domain\Rule;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;

class HttpLinkReader implements LinkReaderInterface
{

    public function __construct(
        private ClientInterface $http,
        private string $baseUrl,
    ) {}

    public function read(string $code): Link
    {
        try {
            $response = $this->http->request('GET', "{$this->baseUrl}/links/{$code}");
        } catch (ClientException $e) {
            if ($e->getResponse()?->getStatusCode() === 404) {
                throw new Exception('Link not found');
            }
            throw $e;
        }

        $data = json_decode((string) $response->getBody(), true)['data'];

        $rules = array_map(
            fn(array $rule) => new Rule(
                expression: $rule['expression'],
                target: $rule['target'],
                priority: (int) ($rule['priority']),
            ),
            $data['rules'],
        );

        return new Link(
            code: $data['code'],
            default: $data['default'],
            rules: $rules,
        );
    }
}
