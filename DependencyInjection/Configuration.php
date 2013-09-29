<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sonatra_bootstrap');

        $rootNode
            ->append($this->getFontNode())
            ->append($this->getAssetNode())
        ;

        return $treeBuilder;
    }

    /**
     * Get fonts node.
     *
     * @return NodeDefinition
     */
    private function getFontNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('font');

        $node
            ->addDefaultsIfNotSet()
            ->fixXmlConfig('path')
            ->children()
                ->scalarNode('output_dir')->defaultValue('font')->end()
                ->arrayNode('paths')
                    ->addDefaultChildrenIfNoneSet()
                    ->prototype('scalar')->defaultValue('%kernel.root_dir%/../vendor/twitter/bootstrap/fonts')->end()
                    ->example(array('%kernel.root_dir%/../vendor/twitter/bootstrap/fonts', '%kernel.root_dir%/../vendor/foo/bar/fonts'))
                    ->validate()
                        ->ifTrue(function($v) { return !in_array('%kernel.root_dir%/../vendor/twitter/bootstrap/fonts', $v); })
                        ->then(function($v){
                            return array_merge(array('%kernel.root_dir%/../vendor/twitter/bootstrap/fonts'), $v);
                        })
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Get assets node.
     *
     * @return NodeDefinition
     */
    private function getAssetNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('common_assets');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('stylesheets')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('inputs')
                            ->fixXmlConfig('input')
                            ->prototype('scalar')->end()
                            ->defaultValue(array())
                        ->end()
                        ->arrayNode('filters')
                            ->fixXmlConfig('filter')
                            ->prototype('scalar')->end()
                            ->defaultValue(array('containerparameter', 'relative', 'cssrewrite', 'lessphp'))
                        ->end()
                        ->arrayNode('options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('output')->defaultValue('css/common.css')->end()
                                ->scalarNode('debug')->defaultNull()->end()
                                ->scalarNode('combine')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('javascripts')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('jquery')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('path')->defaultValue('%kernel.root_dir%/../vendor/sonatra_jquery/jquery/jquery.js')->end()
                            ->end()
                        ->end()
                        ->arrayNode('bootstrap')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('path')->defaultValue('%kernel.root_dir%/../vendor/twitter/bootstrap/js')->end()
                                ->arrayNode('components')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('transition')->defaultTrue()->end()
                                        ->booleanNode('modal')->defaultTrue()->end()
                                        ->booleanNode('dropdown')->defaultTrue()->end()
                                        ->booleanNode('scrollspy')->defaultTrue()->end()
                                        ->booleanNode('tab')->defaultTrue()->end()
                                        ->booleanNode('tooltip')->defaultTrue()->end()
                                        ->booleanNode('popover')->defaultTrue()->end()
                                        ->booleanNode('alert')->defaultTrue()->end()
                                        ->booleanNode('button')->defaultTrue()->end()
                                        ->booleanNode('collapse')->defaultTrue()->end()
                                        ->booleanNode('carousel')->defaultTrue()->end()
                                        ->booleanNode('affix')->defaultTrue()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('inputs')
                            ->fixXmlConfig('input')
                            ->prototype('scalar')->end()
                            ->defaultValue(array())
                        ->end()
                        ->arrayNode('filters')
                            ->fixXmlConfig('filter')
                            ->prototype('scalar')->end()
                            ->defaultValue(array())
                        ->end()
                        ->arrayNode('options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('output')->defaultValue('js/common.js')->end()
                                ->scalarNode('debug')->defaultNull()->end()
                                ->scalarNode('combine')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('hack_lt_ie_9')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('inputs')
                            ->fixXmlConfig('filter')
                            ->prototype('scalar')->end()
                            ->defaultValue(array(
                                '%kernel.root_dir%/../vendor/sonatra_afarkas/html5shiv/src/html5shiv.js',
                                '%kernel.root_dir%/../vendor/sonatra_scottjehl/respond/respond.src.js',
                            ))
                        ->end()
                        ->arrayNode('filters')
                            ->fixXmlConfig('filter')
                            ->prototype('scalar')->end()
                            ->defaultValue(array())
                        ->end()
                        ->arrayNode('options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('output')->defaultValue('js/shiv.js')->end()
                                ->scalarNode('debug')->defaultNull()->end()
                                ->scalarNode('combine')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
