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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sonatra\Bundle\BootstrapBundle\Assetic\Util\ContainerUtils;

/**
 * Replace the container parameter tag (%foo.bar%) in asset.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ContainerParameterFilter implements FilterInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
            return $this->container->getParameter(strtolower($matches[1]));
        });

        $asset->setContent($content);
    }
}
