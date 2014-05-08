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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds all services with the tags "sonatra_bootstrap.%assets_type%.common_localized" as arguments
 * of the "sonatra_bootstrap.assetic.common_localized_%assets_type%s_%locale%_resource" service
 * (with assets_type is stylesheet or javascript and locale is the locale name).
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LocalizedAssetsPass extends AbstractAssetPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('assetic.asset_manager')) {
            return;
        }

        $parameterBag = $container->getParameterBag();
        $cssConfig = $container->getParameter('sonatra_bootstrap.assetic.common_localized_stylesheets_config');
        $jsConfig = $container->getParameter('sonatra_bootstrap.assetic.common_localized_javascripts_config');

        if ($parameterBag instanceof ParameterBag) {
            $parameterBag->remove('sonatra_bootstrap.assetic.common_localized_stylesheets_config');
            $parameterBag->remove('sonatra_bootstrap.assetic.common_localized_javascripts_config');
        }

        // stylesheets
        $this->createAssets($container, 'stylesheet', $cssConfig['localized'], $cssConfig['filters'], $cssConfig['options']);

        // javascripts
        $this->createAssets($container, 'javascript', $jsConfig['localized'], $jsConfig['filters'], $jsConfig['options']);
    }

    /**
     * Create the localized assets.
     *
     * @param ContainerBuilder $container
     * @param string           $type
     * @param array            $localizedAssets
     * @param array            $filters
     * @param array            $options
     *
     * @throws InvalidConfigurationException When the "locale" parameter tag is not present
     */
    protected function createAssets(ContainerBuilder $container, $type, array $localizedAssets, array $filters, array $options)
    {
        $am = $container->getDefinition('assetic.asset_manager');
        $inputs = array();

        foreach ($container->findTaggedServiceIds(sprintf('sonatra_bootstrap.%s.common_localized', $type)) as $serviceId => $tag) {
            if (!isset($tag[0]['locale'])) {
                throw new InvalidConfigurationException(sprintf('The "locale" tag parameter of "%s" service must be present', $serviceId));
            }

            $this->replaceBundleDirectoryResources($container, $container->getDefinition($serviceId));
            $locale = strtolower($tag[0]['locale']);
            $locale = str_replace('-', '_', $locale);

            if (!array_key_exists($locale, $inputs)) {
                $inputs[$locale] = array();
            }

            $inputs[$locale][] = $container->getDefinition($serviceId);
        }

        $inputs = array_merge_recursive($inputs, $localizedAssets);

        if (0 === count($inputs)) {
            return;
        }

        foreach ($inputs as $locale => $assets) {
            $assetOptions = $options;
            $pos = strrpos($options['output'], '.');
            $assetOptions['output'] = substr($options['output'], 0, $pos) . '-' . str_replace('_', '-', $locale) . substr($options['output'], $pos);

            $assetDef = new Definition();
            $assetDef
                ->setClass('Sonatra\Bundle\BootstrapBundle\Assetic\Factory\Resource\SingleConfigurationResource')
                ->setPublic(false)
                ->setTags(array(
                    'assetic.formula_resource' => array(
                        array(
                            'loader' => 'sonatra_bootstrap_config',
                        ),
                    ),
                ))
                ->setArguments(array(
                    sprintf('sonatra_bootstrap_common_localized_%s_%ss', str_replace('-', '_', $locale), $type),
                    $assets,
                    $filters,
                    $assetOptions,
                    new Reference('service_container'),
                    array(),
                ))
            ;

            $id = sprintf('sonatra_bootstrap.assetic.common_localized_%s_%ss_resource', str_replace('-', '_', $locale), $type);
            $container->setDefinition($id, $assetDef);
            $am->addMethodCall('addResource', array(new Reference($id), 'sonatra_bootstrap_config'));
        }
    }
}
