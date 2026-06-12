<?php

namespace Tests\Infrastructure;

use App\Domain\Link;
use App\Infrastructure\LinkRepositoryInterface;
use App\Infrastructure\RedisLinkRepository;
use Exception;
use Override;
use Predis\Client;
use Predis\ClientInterface;
use Tests\TestCase;

class RedisLinkRepositoryTest extends TestCase
{

    private ClientInterface $client;
    private LinkRepositoryInterface $repository;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();
        $app = $this->getAppInstance();
        $this->client = $app->getContainer()->get(Client::class);
        $this->client->flushdb();

        $this->repository = new RedisLinkRepository($this->client);
    }

    public function testCreateSuccess(): void
    {
        $code = uniqid();

        $link = new Link(
            code: $code,
            default: 'default',
            rules: [],
        );

        $this->repository->create($link);

        $createdLink = json_decode($this->client->get($code), true);

        $this->assertEquals($link->code, $createdLink['code']);
        $this->assertEquals($link->default, $createdLink['default']);
        $this->assertEquals($link->rules, $createdLink['rules']);
    }

    public function testGetByCodeSuccess(): void
    {
        $code = uniqid();

        $link = new Link(
            code: $code,
            default: 'default',
            rules: [],
        );

        $this->client->set($code, json_encode($link));

        $createdLink = $this->repository->getByCode($code);

        $this->assertEquals($link, $createdLink);
    }

    public function testThrowExceptionWhenNotFound(): void
    {
        $code = uniqid();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Link not found');

        $this->repository->getByCode($code);
    }
}
