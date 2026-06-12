<?php
declare(strict_types=1);

namespace Tests\Application;

use App\Domain\Link;
use App\Domain\LinkReaderInterface;
use App\Domain\Rule;
use Tests\TestCase;

class RedirectActionTest extends TestCase
{

    public function testReturns302WithMatchingRuleTarget(): void
    {
        $code = uniqid();

        $link = new Link(
            code: $code,
            default: 'https://default.example.com',
            rules: [
                new Rule(
                    expression: "device == 'iOS'",
                    target: 'https://ios.example.com',
                    priority: 1,
                ),
            ],
        );

        $reader = $this->createMock(LinkReaderInterface::class);
        $reader->method('read')->with($code)->willReturn($link);

        $app = $this->getAppInstance();
        $app->getContainer()->set(LinkReaderInterface::class, $reader);

        $request = $this->createRequest('GET', "/{$code}")
            ->withHeader('User-Agent', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0)');

        $response = $app->handle($request);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('https://ios.example.com', $response->getHeaderLine('Location'));
    }

    public function testReturns302WithDefaultWhenNoRuleMatches(): void
    {
        $code = uniqid();

        $link = new Link(
            code: $code,
            default: 'https://default.example.com',
            rules: [
                new Rule(
                    expression: "device == 'iOS'",
                    target: 'https://ios.example.com',
                    priority: 1,
                ),
            ],
        );

        $reader = $this->createMock(LinkReaderInterface::class);
        $reader->method('read')->with($code)->willReturn($link);

        $app = $this->getAppInstance();
        $app->getContainer()->set(LinkReaderInterface::class, $reader);

        $request = $this->createRequest('GET', "/{$code}")
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0)');

        $response = $app->handle($request);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('https://default.example.com', $response->getHeaderLine('Location'));
    }
}
