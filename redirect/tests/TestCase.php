<?php

namespace Tests;

use App\Infrastructure\Http\ContextBuilderMiddleware;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase as FrameworkTestCase;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;

class TestCase extends FrameworkTestCase
{

    protected function getAppInstance(): App
    {
        require __DIR__ . '/../vendor/autoload.php';

        $containerBuilder = new ContainerBuilder();
        (require __DIR__ . '/../app/dependencies.php')($containerBuilder);

        $container = $containerBuilder->build();

        AppFactory::setContainer($container);
        $app = AppFactory::create();

        $app->add(ContextBuilderMiddleware::class);

        (require __DIR__ . '/../routes/index.php')($app);

        return $app;
    }

    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new Request($method, $uri, $h, $cookies, $serverParams, $stream);
    }
}
