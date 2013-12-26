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
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SonatraBootstrapExtension extends Extension implements PrependExtensionInterface
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
        $loader->load('form.xml');
        $loader->load('block.xml');

        $this->configFonts($config['font'], $container);
        $this->configCommonStylesheets($config['common_assets']['stylesheets'], $container);
        $this->configCommonJavascripts($config['common_assets']['javascripts'], $container);
        $this->configHackIe($config['common_assets']['hack_lt_ie_9'], $container);
        $this->configAsseticFilters($config['assetic']['filters'], $container);
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $exts = $container->getExtensions();

        if (isset($exts['twig'])) {
            $resources = array();

            foreach (array('div') as $template) {
                $resources[] = 'SonatraBootstrapBundle:Form:form_bootstrap.html.twig';
            }

            $container->prependExtensionConfig(
                    'twig',
                    array('form' => array('resources' => $resources))
            );
        }

        if (isset($exts['sonatra_block'])) {
            $resources = array(
                'SonatraBootstrapBundle:Block:block_bootstrap.html.twig',
                'SonatraBootstrapBundle:Block:component_bootstrap.html.twig',
            );

            $container->prependExtensionConfig(
                'sonatra_block',
                array('block' => array('resources' => $resources))
            );
        }
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
        if ($container->hasDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource.stylesheet')) {
            if ($btConfig['build']) {
                $container->getDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource.stylesheet')->replaceArgument(0, $btConfig['cache_directory']);
                $container->getDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource.stylesheet')->replaceArgument(1, $btConfig['directory']);
                $container->getDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource.stylesheet')->replaceArgument(2, $btConfig['components']);

            } else {
                $container->removeDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource.stylesheet');
            }
        }

        //theme
        if ($container->hasDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource.stylesheet_theme')) {
            if ($btConfig['build'] && $btConfig['theme']) {
                $container->getDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource.stylesheet_theme')->replaceArgument(0, $btConfig['cache_directory']);
                $container->getDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource.stylesheet_theme')->replaceArgument(1, $btConfig['directory']);
                $container->getDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource.stylesheet_theme')->replaceArgument(2, $btConfig['theme']);

            } else {
                $container->removeDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource.stylesheet_theme');
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

        // jquery
        $jqueryDef = $this->createFileResourceDefinition($config['jquery']['path'], 'sonatra_bootstrap.javascript.common');
        $container->setDefinition('sonatra_bootstrap.assetic.common_javascripts_resource.jquery', $jqueryDef);

        // bootstrap components
        foreach ($btConfig['components'] as $component => $value) {
            if ($value) {
                $path = sprintf('%s/%s.js', rtrim($btConfig['directory'], '/'), $component);
                $tag = sprintf('sonatra_bootstrap.assetic.common_javascripts_resource.%s', $component);
                $componentDef = $this->createFileResourceDefinition($path, 'sonatra_bootstrap.javascript.common');

                $container->setDefinition($tag, $componentDef);
            }
        }

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
        if (is_string($config['html5_shiv'])) {
            $shivDef = $this->createFileResourceDefinition($config['html5_shiv'], 'sonatra_bootstrap.javascript.shiv');
            $container->setDefinition('sonatra_bootstrap.assetic.hack_lt_ie_9_resource.html5_shiv', $shivDef);
        }

        if (is_string($config['respond'])) {
            $respondDef = $this->createFileResourceDefinition($config['respond'], 'sonatra_bootstrap.javascript.shiv');
            $container->setDefinition('sonatra_bootstrap.assetic.hack_lt_ie_9_resource.respond', $respondDef);
        }

        $container->getDefinition('sonatra_bootstrap.assetic.hack_lt_ie_9_resource')->replaceArgument(1, $config['inputs']);
        $container->getDefinition('sonatra_bootstrap.assetic.hack_lt_ie_9_resource')->replaceArgument(2, $config['filters']);
        $container->getDefinition('sonatra_bootstrap.assetic.hack_lt_ie_9_resource')->replaceArgument(3, $config['options']);
    }

    /**
     * Configures the assetic filters resource.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function configAsseticFilters(array &$config, ContainerBuilder $container)
    {
        $container->getDefinition('sonatra_bootstrap.assetic.oyejorge_lessphp_filter')->replaceArgument(1, $config['oyejorge_lessphp']['options']);
        $container->getDefinition('sonatra_bootstrap.assetic.oyejorge_lessphp_filter')->replaceArgument(2, $config['oyejorge_lessphp']['paths']);
    }

    /**
     * Create assetic file resource definition.
     *
     * @param string $path
     * @param string $tag
     *
     * @return Definition
     */
    protected function createFileResourceDefinition($path, $tag)
    {
        $definition = new Definition();
        $definition
            ->setClass('Assetic\Factory\Resource\FileResource')
            ->setPublic(true)
            ->addArgument($path)
            ->addTag($tag)
        ;

        return $definition;
    }
}
