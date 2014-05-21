<?php

namespace Bravesheep\DatabaseUrlBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BravesheepDatabaseUrlExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $resolver = new DatabaseUrlResolver();
        foreach ($config['urls'] as $name => $url) {
            $target = $url['url'];
            $prefix = $url['prefix'];

            $params = $resolver->resolve($target);
            $container->setParameter("{$prefix}driver", $params['driver']);
            $container->setParameter("{$prefix}host", $params['host']);
            $container->setParameter("{$prefix}port", $params['port']);
            $container->setParameter("{$prefix}name", $params['name']);
            $container->setParameter("{$prefix}user", $params['user']);
            $container->setParameter("{$prefix}password", $params['password']);
            if (is_string($params['path']) && strlen($params['path']) > 1 && substr($params['path'], 0, 2) === './') {
                $params['path'] = '%kernel.root_dir%' . substr($params['path'], 1);
            }
            $container->setParameter("{$prefix}path", $params['path']);
            $container->setParameter("{$prefix}memory", $params['memory']);
        }
    }
}
