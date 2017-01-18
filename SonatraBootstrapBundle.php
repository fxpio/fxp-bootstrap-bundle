<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle;

use Sonatra\Bundle\BootstrapBundle\DependencyInjection\Compiler\BlockTemplatePass;
use Sonatra\Bundle\BootstrapBundle\DependencyInjection\Compiler\ConfigurationPass;
use Sonatra\Bundle\BootstrapBundle\DependencyInjection\Compiler\FormTemplatePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SonatraBootstrapBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FormTemplatePass());
        $container->addCompilerPass(new BlockTemplatePass());
        $container->addCompilerPass(new ConfigurationPass());
    }
}
