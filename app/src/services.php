<?php

declare(strict_types=1);

use App\CliApp;
use App\Defaults;
use App\Domain\ClockService;
use App\Domain\Order\Service\PaymentGatewayClientStub;
use App\HttpApp;
use App\Infra\Cli\GuestCommandInterface;
use App\Infra\Doctrine\EntityManagerFactory;
use App\Infra\Logger\LoggerFactory;
use App\Infra\Routing\ActionInterface;
use App\Infra\Routing\Dispatcher;
use App\Infra\Serialization\SerializerFactory;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Monolog\Level;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Psr\Clock\ClockInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Serializer\SerializerInterface;

use function App\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $appHttpActionTag = 'app.action';
    $services->instanceof(ActionInterface::class)
        ->tag($appHttpActionTag);

    $appCliCommandTag = 'app.cli_command';
    $services->instanceof(Symfony\Component\Console\Command\Command::class)
        ->tag($appCliCommandTag);

    $appCliGuestCommandTag = 'app.cli_guest_command';
    $services->instanceof(GuestCommandInterface::class)
        ->tag($appCliGuestCommandTag);

    $services->load('App\\', __DIR__)
        ->exclude([
            __DIR__ . '/services.php',
            __DIR__ . '/migrations.php',
            __DIR__ . '/functions.php',
            __DIR__ . '/Migrations/*',
            __DIR__ . '/**/Request.php',
            __DIR__ . '/**/Response.php',
            __DIR__ . '/Domain/*/Entity/*',
            __DIR__ . '/**/*DTO.php',
            __DIR__ . '/**/*Event.php',
            __DIR__ . '/**/*Case.php',
        ]);

    $services->set(RequestFactoryInterface::class, Psr17Factory::class);
    $services->set(ResponseFactoryInterface::class, Psr17Factory::class);
    $services->set(ServerRequestFactoryInterface::class, Psr17Factory::class);
    $services->set(StreamFactoryInterface::class, Psr17Factory::class);
    $services->set(UploadedFileFactoryInterface::class, Psr17Factory::class);
    $services->set(UriFactoryInterface::class, Psr17Factory::class);
    $services->set(RequestFactoryInterface::class, Psr17Factory::class);

    $services->set(ServerRequestCreatorInterface::class, ServerRequestCreator::class)
        ->args([
            service(ServerRequestFactoryInterface::class),
            service(UriFactoryInterface::class),
            service(UploadedFileFactoryInterface::class),
            service(StreamFactoryInterface::class),
        ]);

    $services->set(EmitterInterface::class, SapiEmitter::class);

    $services->set(SerializerInterface::class)
        ->factory(service(SerializerFactory::class));

    $services->set(EntityManagerInterface::class)
        ->factory(service(EntityManagerFactory::class));

    $services->set(EntityManagerFactory::class)
        ->args([[
            __DIR__ . '/Domain/Event/Entity',
            __DIR__ . '/Domain/Product/Entity',
            __DIR__ . '/Domain/Order/Entity',
        ]]);

    $services->set(ClockInterface::class, ClockService::class);

    $services->set(HttpApp::class)
        ->public();

    $services->set(CliApp::class)
        ->args([
            tagged_iterator($appCliCommandTag),
            tagged_iterator($appCliGuestCommandTag)
        ])
        ->public();

    $services->set(Dispatcher::class)
        ->args([tagged_iterator($appHttpActionTag)]);

    $services->set(LoggerInterface::class)
        ->factory(service(LoggerFactory::class));

    $services->set(LoggerFactory::class)
        ->args([
            $logName = env(Defaults::ENV_LOG_NAME_PARAM, fn($v) => $v ?? 'products_api'),
            env(Defaults::ENV_LOG_PATH_PARAM, fn($v) => $v ?? __DIR__ . "/../var/{$logName}.log"),
            env(
                Defaults::ENV_LOG_LEVEL_PARAM,
                function ($v) {
                    return $v === null
                        ? Level::fromName('INFO')
                        : (in_array(strtoupper($v), Level::NAMES)
                            /** @phpstan-ignore-next-line */
                            ? Level::fromName($v)
                            : throw new \RuntimeException("Undefined log level '$v'"));
                }
            )
        ]);

    $services->set(PaymentGatewayClientStub::class)->args([
        env(
            Defaults::ENV_PAYMENT_GATEWAY_URL_PARAM,
            fn($v) => $v ?? 'http://gateway-stub'
        )
    ]);
};
