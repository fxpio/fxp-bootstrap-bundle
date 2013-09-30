<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Assetic\Filter;

use Assetic\Filter\FilterInterface;
use Assetic\Asset\AssetInterface;
use Symfony\Component\DependencyInjection\Container;
use Sonatra\Bundle\BootstrapBundle\Assetic\Util\ContainerUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Replace the parameter bag tag (%foo.bar%) in asset.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ParameterBagFilter implements FilterInterface
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(Container $container)
    {
        $this->parameterBag = $container->getParameterBag();
    }

    /**
     * {@inheritdoc}
     */
    public function filterLoad(AssetInterface $asset)
    {
        $this->filterDump($asset);
    }

    /**
     * {@inheritdoc}
     */
    public function filterDump(AssetInterface $asset)
    {
        $content = ContainerUtils::filterParameters($asset->getContent(), function($matches) {
            return $this->parameterBag->get(strtolower($matches[1]));
        });

        $asset->setContent($content);
    }
}
