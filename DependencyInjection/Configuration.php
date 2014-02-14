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
            ->append($this->getAsseticNode())
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
                        ->ifTrue(function ($v) { return !in_array('%kernel.root_dir%/../vendor/twitter/bootstrap/fonts', $v); })
                        ->then(function ($v) {
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
                        ->arrayNode('bootstrap')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('build')->defaultTrue()->end()
                                ->scalarNode('cache_directory')->defaultValue('%kernel.cache_dir%/sonatra_bootstrap/less')->end()
                                ->scalarNode('directory')->defaultValue('%kernel.root_dir%/../vendor/twitter/bootstrap/less')->end()
                                ->booleanNode('theme')->defaultFalse()->end()
                                ->scalarNode('addon')
                                    ->defaultValue('@SonatraBootstrapBundle/Resources/assetic/less/addon.less')
                                    ->example('"@AcmeDemoBundle/Resources/assetic/less/addon.less" or false')
                                ->end()
                                ->arrayNode('components')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('variables')->defaultValue('@SonatraBootstrapBundle/Resources/assetic/less/variables.less')->end()
                                        ->scalarNode('custom_variables')->defaultNull()->end()
                                        ->booleanNode('mixins')->defaultTrue()->end()
                                        ->scalarNode('custom_mixins')->defaultNull()->end()
                                        ->booleanNode('normalize')->defaultTrue()->end()
                                        ->booleanNode('print')->defaultTrue()->end()
                                        ->booleanNode('scaffolding')->defaultTrue()->end()
                                        ->booleanNode('type')->defaultTrue()->end()
                                        ->booleanNode('code')->defaultTrue()->end()
                                        ->booleanNode('grid')->defaultTrue()->end()
                                        ->booleanNode('tables')->defaultTrue()->end()
                                        ->booleanNode('forms')->defaultTrue()->end()
                                        ->booleanNode('buttons')->defaultTrue()->end()
                                        ->booleanNode('component_animations')->defaultTrue()->info('For javascript')->end()
                                        ->booleanNode('glyphicons')->defaultTrue()->end()
                                        ->booleanNode('dropdowns')->defaultTrue()->end()
                                        ->booleanNode('button_groups')->defaultTrue()->end()
                                        ->booleanNode('input_groups')->defaultTrue()->end()
                                        ->booleanNode('navs')->defaultTrue()->end()
                                        ->booleanNode('navbar')->defaultTrue()->end()
                                        ->booleanNode('breadcrumbs')->defaultTrue()->end()
                                        ->booleanNode('pagination')->defaultTrue()->end()
                                        ->booleanNode('pager')->defaultTrue()->end()
                                        ->booleanNode('labels')->defaultTrue()->end()
                                        ->booleanNode('badges')->defaultTrue()->end()
                                        ->booleanNode('jumbotron')->defaultTrue()->end()
                                        ->booleanNode('thumbnails')->defaultTrue()->end()
                                        ->booleanNode('alerts')->defaultTrue()->end()
                                        ->booleanNode('progress_bars')->defaultTrue()->end()
                                        ->booleanNode('media')->defaultTrue()->end()
                                        ->booleanNode('list_group')->defaultTrue()->end()
                                        ->booleanNode('panels')->defaultTrue()->end()
                                        ->booleanNode('wells')->defaultTrue()->end()
                                        ->booleanNode('close')->defaultTrue()->end()
                                        ->booleanNode('modals')->defaultTrue()->end()
                                        ->booleanNode('tooltip')->defaultTrue()->end()
                                        ->booleanNode('popovers')->defaultTrue()->end()
                                        ->booleanNode('carousel')->defaultTrue()->end()
                                        ->booleanNode('utilities')->defaultTrue()->end()
                                        ->booleanNode('responsive_utilities')->defaultTrue()->end()
                                        ->scalarNode('blocks')->defaultValue('@SonatraBootstrapBundle/Resources/assetic/less/blocks.less')->end()
                                        ->scalarNode('addon')->defaultValue('@SonatraBootstrapBundle/Resources/assetic/less/addon.less')->end()
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
                            ->defaultValue(array('parameterbag', 'bundle', 'relative', 'cssrewrite', 'oyejorge_lessphp'))
                            ->info('The filters "parameterbag", "bundle" and "relative" must be present in this order. Otherwise, they will automatically be added to first place')
                            ->validate()
                                ->always()
                                ->then(function ($v) {
                                    if (!in_array('less', $v) && !in_array('lessphp', $v) && !in_array('oyejorge_lessphp', $v)) {
                                        array_unshift($v, 'oyejorge_lessphp');
                                    }

                                    if (!in_array('relative', $v)) {
                                        array_unshift($v, 'relative');
                                    }

                                    if (!in_array('bundle', $v)) {
                                        array_unshift($v, 'bundle');
                                    }

                                    if (!in_array('parameterbag', $v)) {
                                        array_unshift($v, 'parameterbag');
                                    }

                                    return $v;
                                })
                            ->end()
                        ->end()
                        ->arrayNode('options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('output')->defaultValue('css/common.css')->end()
                                ->scalarNode('debug')->defaultNull()->end()
                                ->scalarNode('combine')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('localized')
                            ->validate()
                                ->always()
                                ->then(function ($v) {
                                    return array_change_key_case($v, CASE_LOWER);
                                })
                            ->end()
                            ->prototype('array')
                                ->prototype('scalar')->end()
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
                                ->scalarNode('directory')->defaultValue('%kernel.root_dir%/../vendor/twitter/bootstrap/js')->end()
                                ->arrayNode('components')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('transition')->defaultTrue()->info('Required for any kind of animation')->end()
                                        ->booleanNode('modal')->defaultTrue()->end()
                                        ->booleanNode('dropdown')->defaultTrue()->end()
                                        ->booleanNode('scrollspy')->defaultTrue()->end()
                                        ->booleanNode('tab')->defaultTrue()->end()
                                        ->booleanNode('tooltip')->defaultTrue()->end()
                                        ->booleanNode('popover')->defaultTrue()->info('Requires Tooltips')->end()
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
                        ->arrayNode('localized')
                            ->validate()
                                ->always()
                                ->then(function ($v) {
                                    return array_change_key_case($v, CASE_LOWER);
                                })
                            ->end()
                            ->prototype('array')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('hack_lt_ie_9')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('html5_shiv')->defaultValue('%kernel.root_dir%/../vendor/sonatra_afarkas/html5shiv/src/html5shiv.js')->end()
                        ->scalarNode('respond')->defaultValue('%kernel.root_dir%/../vendor/sonatra_scottjehl/respond/src/respond.js')->end()
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

    /**
     * Get assetic filter node.
     *
     * @return NodeDefinition
     */
    private function getAsseticNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('assetic');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('filters')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('oyejorge_lessphp')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('options')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->booleanNode('compress')->defaultFalse()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('paths')
                                    ->fixXmlConfig('path')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
