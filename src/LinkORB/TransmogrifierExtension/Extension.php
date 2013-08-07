<?php

namespace LinkORB\TransmogrifierExtension;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Behat\Behat\Extension\ExtensionInterface;

class Extension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/services'));
        $loader->load('core.xml');

        if (isset($config['dbconf_dir'])) {
            $container->setParameter('behat.transmogrifierextension.dbconf_dir', rtrim($config['dbconf_dir'], '/'));
        }

        if (isset($config['dataset_dir'])) {
            $container->setParameter('behat.transmogrifierextension.dataset_dir', rtrim($config['dataset_dir'], '/'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder->
            children()->
                scalarNode('dbconf_dir')->
                    defaultNull()->
                end()->
                scalarNode('dataset_dir')->
                    defaultNull()->
                end()->
             end()->
        end();
    }

    /**
     * {@inheritdoc}
     */
    public function getCompilerPasses()
    {
        return array();
    }
}
