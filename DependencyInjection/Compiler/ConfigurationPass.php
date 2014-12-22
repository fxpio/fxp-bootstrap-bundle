<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigurationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('sonatra_bootstrap.config.auto_configuration')) {
            $this->processDefault($container);
            $this->processPackages($container);
            $this->processAssetReplacement($container);
        }

        /* @var ParameterBag $pb */
        $pb = $container->getParameterBag();
        $pb->remove('sonatra_bootstrap.config.auto_configuration');
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function processDefault(ContainerBuilder $container)
    {
        $extManagerDef = $container->getDefinition('fxp_require_asset.assetic.config.file_extension_manager');
        $configs = array(
            'md'   => array(
                'exclude'   => true,
            ),
            'css'  => array(
                'filters'   => array('requirecssrewrite'),
            ),
            'less' => array(
                'filters'   => array('lessvariable', 'parameterbag', 'less', 'requirecssrewrite'),
                'extension' => 'css',
            ),
        );

        $extManagerDef->addMethodCall('addDefaultExtensions', array($configs));
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function processPackages(ContainerBuilder $container)
    {
        $packageManagerDef = $container->getDefinition('fxp_require_asset.assetic.config.package_manager');
        $packages = array(
            array(
                'name' => '@bower/html5shiv',
                'patterns' => array(
                    'dist/html5shiv.js',
                ),
            ),
            array(
                'name' => '@bower/respond',
                'patterns' => array(
                    'dest/respond.src.js',
                ),
            ),
            array(
                'name' => '@bower/jquery',
                'patterns' => array(
                    'dist/jquery.js',
                ),
            ),
            array(
                'name' => '@bower/bootstrap',
                'patterns' => array(
                    'js/*',
                    'fonts/*',
                ),
            ),
            array(
                'name' => 'sonatra_block_bundle',
                'patterns' => array(
                    '!icons/*',
                ),
            ),
            array(
                'name' => 'sonatra_bootstrap_bundle',
                'patterns' => array(
                    'assetic/less/bootstrap.less',
                ),
            ),
        );

        $packageManagerDef->addMethodCall('addPackages', array($packages));
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function processAssetReplacement(ContainerBuilder $container)
    {
        $replacementManagerDef = $container->getDefinition('fxp_require_asset.assetic.config.asset_replacement_manager');
        $replacement = array(
            '@bower/bootstrap/less/bootstrap.less' => 'sonatra_bootstrap_bundle/assetic/less/bootstrap.less',
        );

        $replacementManagerDef->addMethodCall('addReplacements', array($replacement));
    }
}
