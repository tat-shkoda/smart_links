<?php
declare(strict_types=1);

namespace Tests\Application;

use Exception;
use Predis\Client;
use Slim\App;
use Tests\TestCase;

final class GetLinkByCodeActionTest extends TestCase
{

    private App $app;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = $this->getAppInstance();
        $this->app->getContainer()->get(Client::class)->flushdb();
    }

    public function testGetSuccess(): void
    {
        $code = 'code';

        $payload = [
            'code' => $code,
            'default' => 'https://example.com',
            'rules' => [
                [
                    'expression' => "device == 'iOS'",
                    'target' => 'https://ios.example.com',
                    'priority' => 1,
                ],
            ],
        ];

        $createRequest = $this->createRequest('POST', '/links')
            ->withParsedBody($payload);
        $this->app->handle($createRequest);

        $request = $this->createRequest('GET', '/links/' . $code);
        $response = $this->app->handle($request);
        $data = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertIsArray($data);
        $this->assertSame('ok', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertSame($code, $data['data']['code']);
        $this->assertSame('https://example.com', $data['data']['default']);
    }

    public function testThrowErrorWhenLinkNotFound(): void
    {
        $request = $this->createRequest('GET', '/links/undefined');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Link not found');

        $this->app->handle($request);
    }
}
