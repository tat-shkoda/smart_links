<?php

namespace App\Infrastructure;

use App\Domain\Link;
use Exception;
use Predis\ClientInterface;

class RedisLinkRepository implements LinkRepositoryInterface
{

    public function __construct(
        private ClientInterface $client,
    ) {}

    public function create(Link $link): void
    {
        $this->client->set($link->code, json_encode($link));
    }

    public function exists(string $code): bool
    {
        return $this->client->exists($code) > 0;
    }

    public function getByCode(string $code): Link
    {
        $item = $this->client->get($code);

        if ($item === null) {
            throw new Exception('Link not found');
        }

        $link = json_decode($item);

        return new Link(
            code: $code,
            default: $link->default,
            rules: $link->rules,
        );
    }
}
