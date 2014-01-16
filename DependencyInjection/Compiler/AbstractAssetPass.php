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
use Symfony\Component\DependencyInjection\Definition;
use Sonatra\Bundle\BootstrapBundle\Assetic\Util\ContainerUtils;

/**
 * Abstract class for asset compiler pass.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class AbstractAssetPass implements CompilerPassInterface
{
    /**
     * Replace "@AcmeDemoBundle" bundle pattern by real directory.
     *
     * @param ContainerBuilder $container
     * @param string           $arg
     *
     * @return string
     */
    protected function replaceBundleDirectoryResources(ContainerBuilder $container, Definition $definition)
    {
        $bundles = $container->getParameter('kernel.bundles');

        foreach ($definition->getArguments() as $index => $argument) {
            if (is_string($argument)) {
                $argument = ContainerUtils::filterBundles($argument, function ($matches) use ($bundles) {
                    $bundle = $matches[1] . 'Bundle';

                    if (isset($bundles[$bundle])) {
                        $ref = new \ReflectionClass($bundles[$bundle]);

                        return str_replace('\\', '/', dirname($ref->getFileName()));
                    }

                    return $matches[0];
                });

                $definition->replaceArgument($index, $argument);
            }
        }
    }
}
