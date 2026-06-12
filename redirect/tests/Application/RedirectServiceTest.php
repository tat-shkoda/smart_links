<?php
declare(strict_types=1);

namespace Tests\Application;

use App\Application\RedirectService;
use App\Domain\Context;
use App\Domain\Link;
use App\Domain\LinkReaderInterface;
use App\Domain\Rule;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Tests\TestCase;

class RedirectServiceTest extends TestCase
{

    public function testWhenRuleMatches(): void
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

        $service = new RedirectService($reader, new ExpressionLanguage());

        $context = new Context();
        $context->set('device', 'iOS');

        $target = $service->resolve($code, $context);

        $this->assertSame('https://ios.example.com', $target);
    }

    public function testWhenNoRuleMatches(): void
    {
        $code = uniqid();

        $default = 'https://default.example.com';
        $link = new Link(
            code: $code,
            default: $default,
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

        $service = new RedirectService($reader, new ExpressionLanguage());

        $context = new Context();
        $context->set('device', 'Android');

        $target = $service->resolve($code, $context);

        $this->assertSame($default, $target);
    }

    public function testPriority(): void
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
                new Rule(
                    expression: "hour == '3'",
                    target: 'https://hour.example.com',
                    priority: 2,
                ),
            ],
        );

        $reader = $this->createMock(LinkReaderInterface::class);
        $reader->method('read')->with($code)->willReturn($link);

        $service = new RedirectService($reader, new ExpressionLanguage());

        $context = new Context();
        $context->set('device', 'iOS');
        $context->set('hour', '3');

        $target = $service->resolve($code, $context);

        $this->assertSame('https://hour.example.com', $target);
    }
}
