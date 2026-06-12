<?php
declare(strict_types=1);

namespace Tests\Application;

use App\Application\Validation\DuplicateCode;
use App\Application\Validation\InvalidExpression;
use Predis\Client;
use Slim\App;
use Tests\TestCase;

final class CreateLinkActionTest extends TestCase
{

    private App $app;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = $this->getAppInstance();
        $this->app->getContainer()->get(Client::class)->flushdb();
    }

    public function testCreateSuccess(): void
    {
        $code = uniqid();

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

        $request = $this->createRequest('POST', '/links')
            ->withParsedBody($payload);
        $response = $this->app->handle($request);
        $data = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertIsArray($data);
        $this->assertSame('ok', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('code', $data['data']);
        $this->assertSame($code, $data['data']['code']);
    }

    public function testThrowErrorWhenInvalidRule(): void
    {
        $code = uniqid();

        $payload = [
            'code' => $code,
            'default' => 'https://example.com',
            'rules' => [
                [
                    'expression' => 'temperature > 20',
                    'target' => 'https://ios.example.com',
                    'priority' => 1,
                ],
            ],
        ];

        $request = $this->createRequest('POST', '/links')
            ->withParsedBody($payload);

        $this->expectException(InvalidExpression::class);

        $this->app->handle($request);
    }

    public function testThrowErrorWhenCodeAlreadyExists(): void
    {
        $code = uniqid();

        $payload = [
            'code' => $code,
            'default' => 'https://example.com',
            'rules' => [],
        ];

        $firstRequest = $this->createRequest('POST', '/links')
            ->withParsedBody($payload);
        $this->app->handle($firstRequest);

        $secondRequest = $this->createRequest('POST', '/links')
            ->withParsedBody($payload);

        $this->expectException(DuplicateCode::class);

        $this->app->handle($secondRequest);
    }
}
