<?php

declare(strict_types=1);

use App\Infrastructure\Http\ContextBuilderMiddleware;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
(require __DIR__ . '/../app/dependencies.php')($builder);

$container = $builder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->add(ContextBuilderMiddleware::class);

(require __DIR__ . '/../routes/index.php')($app);

$app->run();
