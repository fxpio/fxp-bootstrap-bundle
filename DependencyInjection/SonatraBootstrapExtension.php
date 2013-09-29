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

        // fonts config
        $container->setParameter('sonatra_bootstrap.assetic.font_paths', $config['font']['paths']);
        $container->setParameter('sonatra_bootstrap.assetic.font_output', trim($config['font']['output_dir'], '/'));

        //assets config
        $config['common_assets']['stylesheets']['inputs'] = array(
            '@SonatraBootstrapBundle/Resources/assetic/less/bootstrap.less',
        );

        $btConfig = $config['common_assets']['javascripts']['bootstrap'];
        $btPath = $btConfig['path'];
        $jsInputs = array(
            $config['common_assets']['javascripts']['jquery']['path'],
        );

        foreach ($btConfig['components'] as $component => $value) {
            if ($value) {
                $jsInputs[] = rtrim($btPath, '/') . '/' . $component . '.js';
            }
        }

        $config['common_assets']['javascripts']['inputs'] = array_merge($jsInputs,
            $config['common_assets']['javascripts']['inputs']);

        $assets = array(
            'sonatra_bootstrap_common_stylesheets' => array(
                'inputs'  => $config['common_assets']['stylesheets']['inputs'],
                'filters' => $config['common_assets']['stylesheets']['filters'],
                'options' => $config['common_assets']['stylesheets']['options'],
            ),
            'sonatra_bootstrap_common_javascripts' => array(
                'inputs'  => $config['common_assets']['javascripts']['inputs'],
                'filters' => $config['common_assets']['javascripts']['filters'],
                'options' => $config['common_assets']['javascripts']['options'],
            ),
            'sonatra_bootstrap_head_hack_lt_ie_9' => array(
                'inputs'  => $config['common_assets']['hack_lt_ie_9']['inputs'],
                'filters' => $config['common_assets']['hack_lt_ie_9']['filters'],
                'options' => $config['common_assets']['hack_lt_ie_9']['options'],
            ),
        );

        // register assetic formulae
        $formulae = array();

        foreach ($assets as $name => $formula) {
            $formulae[$name] = array($formula['inputs'], $formula['filters'], $formula['options']);
        }

        $container->getDefinition('sonatra_bootstrap.assetic.config_resource')->replaceArgument(0, $formulae);
    }
}
