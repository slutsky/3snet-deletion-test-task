<?php

declare(strict_types=1);

namespace Shared\Deletion;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Kernel\AbstractBundle;
use Symfony\Component\DependencyInjection\Kernel\BundleInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

class SharedDeletionBundle extends AbstractBundle implements BundleInterface
{
    public function getNamespace(): string
    {
        return '\\Shared\\Deletion';
    }

    public function getPath(): string
    {
        return __DIR__;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');

        parent::loadExtension($config, $container, $builder);
    }
}
