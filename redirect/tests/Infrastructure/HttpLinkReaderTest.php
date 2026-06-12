<?php
declare(strict_types=1);

namespace Tests\Infrastructure;

use App\Domain\Link;
use App\Infrastructure\HttpLinkReader;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class HttpLinkReaderTest extends TestCase
{

    public function testReadLinkSuccess(): void
    {
        $code = uniqid();

        $response = json_encode([
            'status' => 'ok',
            'data' => [
                'code' => $code,
                'default' => 'https://example.com',
                'rules' => [
                    [
                        'expression' => "device == 'iOS'",
                        'target' => 'https://ios.example.com',
                        'priority' => 1,
                    ],
                ],
            ],
        ]);

        $mock = new MockHandler([new Response(200, [], $response)]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $reader = new HttpLinkReader($client, 'http://admin:8080');

        $link = $reader->read($code);

        $this->assertInstanceOf(Link::class, $link);
        $this->assertSame($code, $link->code);
        $this->assertSame('https://example.com', $link->default);
        $this->assertCount(1, $link->rules);
        $this->assertSame("device == 'iOS'", $link->rules[0]->expression);
        $this->assertSame('https://ios.example.com', $link->rules[0]->target);
        $this->assertSame(1, $link->rules[0]->priority);
    }

    public function testThrowExceptionWhenLinkNotFound(): void
    {
        $mock = new MockHandler([new Response(404, [], '')]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $reader = new HttpLinkReader($client, 'http://admin:8080');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Link not found');

        $reader->read('nonexistent');
    }
}
