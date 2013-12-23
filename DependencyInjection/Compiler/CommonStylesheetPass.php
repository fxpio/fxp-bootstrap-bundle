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

/**
 * Adds all services with the tags "sonatra_bootstrap.stylesheet.common" as arguments
 * of the "sonatra_bootstrap.assetic.common_stylesheets_resource" service.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CommonStylesheetPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource')) {
            return;
        }

        $builders = array();

        foreach ($container->findTaggedServiceIds('sonatra_bootstrap.stylesheet.common') as $serviceId => $tag) {
            $builders[] = $serviceId;
        }

        $container->getDefinition('sonatra_bootstrap.assetic.common_stylesheets_resource')->replaceArgument(5, $builders);
    }
}
