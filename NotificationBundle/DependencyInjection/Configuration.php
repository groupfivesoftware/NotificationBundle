<?php
/**
 * Created by PhpStorm.
 * User: laurentiu
 * Date: 4/21/16
 * Time: 9:00 AM
 */

namespace NotificationBundle\DependencyInjection;


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