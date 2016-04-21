<?php

namespace GFS\NotificationBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface{

    public function getConfigTreeBuilder(){
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gfs_notifications');

        $rootNode->children()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->scalarNode('port')->defaultValue('8080')->end()
            ->end();

        return $treeBuilder;
    }
} 