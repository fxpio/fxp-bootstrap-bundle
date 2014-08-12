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
use Assetic\Filter\HashableInterface;
use Symfony\Component\DependencyInjection\Container;
use Sonatra\Bundle\BootstrapBundle\Assetic\Util\ContainerUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Replace the parameter bag tag (%foo.bar%) in asset.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ParameterBagFilter implements FilterInterface, HashableInterface
{
    /**
     * @var Container
     */
    public $container;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

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
        $parameterBag = $this->getParameterBag();
        $content = ContainerUtils::filterParameters($asset->getContent(), function ($matches) use ($parameterBag) {
            return $parameterBag->get(strtolower($matches[1]));
        });

        $asset->setContent($content);
    }

    public function hash()
    {
        $this->getParameterBag();

        return serialize($this);
    }

    /**
     * Gets parameter bag.
     *
     * @return ParameterBagInterface
     */
    private function getParameterBag()
    {
        if (null === $this->parameterBag) {
            $this->parameterBag = $this->container->getParameterBag();
            $this->container = null;
        }

        return $this->parameterBag;
    }
}
