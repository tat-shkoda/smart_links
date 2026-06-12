<?php
declare(strict_types=1);

use App\Application\Dsl\DslFunctionRegistry;
use App\Application\Dsl\Functions\IsWeekendFunction;
use App\Application\Services\CreateLinkService;
use App\Application\Services\CreateLinkServiceInterface;
use App\Application\Services\GetLinkByCodeService;
use App\Application\Services\GetLinkByCodeServiceInterface;
use App\Application\Validation\RuleValidator;
use App\Infrastructure\LinkRepositoryInterface;
use App\Infrastructure\RedisLinkRepository;
use DI\ContainerBuilder;
use Predis\Client;
use Predis\ClientInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

use function DI\autowire;
use function DI\get;

return function (ContainerBuilder $builder) {
    $builder->addDefinitions([
        Client::class => fn() => new Client('tcp://redis:6379'),
        ClientInterface::class => get(Client::class),

        LinkRepositoryInterface::class => autowire(RedisLinkRepository::class),
        CreateLinkServiceInterface::class => autowire(CreateLinkService::class),
        GetLinkByCodeServiceInterface::class => autowire(GetLinkByCodeService::class),

        'dsl.functions' => [
            get(IsWeekendFunction::class),
        ],

        DslFunctionRegistry::class => function (ContainerInterface $c) {
            return new DslFunctionRegistry($c->get('dsl.functions'));
        },

        ExpressionLanguage::class => function (ContainerInterface $c) {
            $el = new ExpressionLanguage();
            $registry = $c->get(DslFunctionRegistry::class);

            foreach ($registry->all() as $function) {
                $el->register(
                    $function->getName(),
                    fn() => '',
                    fn(array $ctx, mixed ...$args) => $function->evaluate($ctx, ...$args),
                );
            }

            return $el;
        },

        RuleValidator::class => function (ContainerInterface $c) {
            $dsl = require __DIR__ . '/dsl.php';
            return new RuleValidator(
                $c->get(ExpressionLanguage::class),
                $dsl['variables'],
            );
        },
    ]);
};