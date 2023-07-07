<?php

declare(strict_types=1);

namespace App;

use Psr\Container\ContainerInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

abstract class AbstractApp implements AppInterface
{
    protected bool $isDebug = false;

    public static function boot(
        string $cacheDir = null,
        bool $isDebug = false,
    ): static {
        $cacheDir = $cacheDir ?? __DIR__ . '/../var';
        $file = $cacheDir . '/container.php';
        $configCache = new ConfigCache($file, $isDebug);

        if (!$configCache->isFresh()) {
            $container = new ContainerBuilder();
            $loader  = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../src'));
            $loader->load('services.php');
            $container->compile();

            $dumper = new PhpDumper($container);
            $data = $dumper->dump(['class' => 'MyCachedContainer']);
            if (!is_string($data)) {
                throw new \RuntimeException(
                    "Container dump is expected to be a string"
                );
            }
            $configCache->write(
                $data,
                $container->getResources()
            );
        }

        include_once $file;
        /** @phpstan-ignore-next-line */
        $container = new \MyCachedContainer();
        /** @var ContainerInterface $container */
        $app = $container->get(static::class);
        /** @var AbstractApp $app */
        $app->isDebug = $isDebug;
        /** @phpstan-ignore-next-line */
        return $app;
    }
}
