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

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sonatra\Bundle\BootstrapBundle\DependencyInjection\Compiler\FormTemplatePass;
use Sonatra\Bundle\BootstrapBundle\DependencyInjection\Compiler\BlockTemplatePass;
use Sonatra\Bundle\BootstrapBundle\DependencyInjection\Compiler\CommonStylesheetPass;
use Sonatra\Bundle\BootstrapBundle\DependencyInjection\Compiler\CommonJavascriptPass;
use Sonatra\Bundle\BootstrapBundle\DependencyInjection\Compiler\LocalizedAssetsPass;
use Sonatra\Bundle\BootstrapBundle\DependencyInjection\Compiler\ShivJavascriptPass;

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
        $container->addCompilerPass(new CommonStylesheetPass());
        $container->addCompilerPass(new CommonJavascriptPass());
        $container->addCompilerPass(new LocalizedAssetsPass());
        $container->addCompilerPass(new ShivJavascriptPass());
    }
}
