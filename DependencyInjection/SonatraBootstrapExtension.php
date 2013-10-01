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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SonatraBootstrapExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('assetic.xml');
        $loader->load('templating_twig.xml');

        $this->configFonts($config['font'], $container);
        $this->configCommonStylesheets($config['common_assets']['stylesheets'], $container);
        $this->configCommonJavascripts($config['common_assets']['javascripts'], $container);
        $this->configHackIe($config['common_assets']['hack_lt_ie_9'], $container);
    }

    /**
     * Configures the fonts resource.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function configFonts(array &$config, ContainerBuilder $container)
    {
        $container->setParameter('sonatra_bootstrap.assetic.font_output', trim($config['output_dir'], '/'));
        $container->getDefinition('sonatra_bootstrap.assetic.font_resource')->replaceArgument(0, $config['paths']);
    }

    /**
     * Configures the common stylesheets resource.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function configCommonStylesheets(array &$config, ContainerBuilder $container)
    {
        $btConfig = &$config['bootstrap'];

        // bootstrap
        if ($container->hasDefinition('sonatra_bootstrap.builder.stylesheet')) {
            if ($btConfig['build']) {
                $container->getDefinition('sonatra_bootstrap.builder.stylesheet')->replaceArgument(0, $btConfig['cache_directory']);
                $container->getDefinition('sonatra_bootstrap.builder.stylesheet')->replaceArgument(1, $btConfig['directory']);
                $container->getDefinition('sonatra_bootstrap.builder.stylesheet')->replaceArgument(2, $btConfig['components']);

            } else {
                $container->removeDefinition('sonatra_bootstrap.builder.stylesheet');
            }
        }

        //theme
        if ($container->hasDefinition('sonatra_bootstrap.builder.stylesheet_theme')) {
            if ($btConfig['build'] && $btConfig['theme']) {
                $container->getDefinition('sonatra_bootstrap.builder.stylesheet_theme')->replaceArgument(0, $btConfig['cache_directory']);
                $container->getDefinition('sonatra_bootstrap.builder.stylesheet_theme')->replaceArgument(1, $btConfig['directory']);
                $container->getDefinition('sonatra_bootstrap.builder.stylesheet_theme')->replaceArgument(2, $btConfig['theme']);

            } else {
                $container->removeDefinition('sonatra_bootstrap.builder.stylesheet_theme');
            }
        }

        $container->getDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource')->replaceArgument(1, $config['inputs']);
        $container->getDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource')->replaceArgument(2, $config['filters']);
        $container->getDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource')->replaceArgument(3, $config['options']);
    }

    /**
     * Configures the common javascripts resource.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function configCommonJavascripts(array &$config, ContainerBuilder $container)
    {
        $btConfig = &$config['bootstrap'];
        $jsInputs = array(
            $config['jquery']['path'],
        );

        foreach ($btConfig['components'] as $component => $value) {
            if ($value) {
                $jsInputs[] = sprintf('%s/%s.js', rtrim($btConfig['directory'], '/'), $component);
            }
        }

        $config['inputs'] = array_merge($jsInputs, $config['inputs']);

        $container->getDefinition('sonatra_bootstrap.assetic.common_javascripts_resource')->replaceArgument(1, $config['inputs']);
        $container->getDefinition('sonatra_bootstrap.assetic.common_javascripts_resource')->replaceArgument(2, $config['filters']);
        $container->getDefinition('sonatra_bootstrap.assetic.common_javascripts_resource')->replaceArgument(3, $config['options']);
    }

    /**
     * Configures the hack ie resource.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function configHackIe(array &$config, ContainerBuilder $container)
    {
        $container->getDefinition('sonatra_bootstrap.assetic.hack_lt_ie_9_resource')->replaceArgument(1, $config['inputs']);
        $container->getDefinition('sonatra_bootstrap.assetic.hack_lt_ie_9_resource')->replaceArgument(2, $config['filters']);
        $container->getDefinition('sonatra_bootstrap.assetic.hack_lt_ie_9_resource')->replaceArgument(3, $config['options']);
    }
}
