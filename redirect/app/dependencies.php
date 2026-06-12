<?php

use App\Domain\LinkReaderInterface;
use App\Infrastructure\CachedLinkReader;
use App\Infrastructure\Context\DeviceProvider;
use App\Infrastructure\Context\HourProvider;
use App\Infrastructure\Context\WeekdayProvider;
use App\Infrastructure\Http\ContextBuilderMiddleware;
use App\Infrastructure\HttpLinkReader;
use DI\ContainerBuilder;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

use function DI\get;

return function (ContainerBuilder $builder) {
    $builder->addDefinitions([
        'context.providers' => [
            DI\get(DeviceProvider::class),
            DI\get(HourProvider::class),
            DI\get(WeekdayProvider::class),
        ],

        ContextBuilderMiddleware::class => function (ContainerInterface $c) {
            return new ContextBuilderMiddleware($c->get('context.providers'));
        },

        HttpLinkReader::class => fn() => new HttpLinkReader(new Client(), 'http://admin:8080'),

        CachedLinkReader::class => function (ContainerInterface $c) {
            return new CachedLinkReader($c->get(HttpLinkReader::class));
        },

        LinkReaderInterface::class => get(CachedLinkReader::class),
    ]);
};