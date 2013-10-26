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
use Sonatra\Bundle\BootstrapBundle\Assetic\Util\ContainerUtils;

/**
 * Replace the bundle alias (@AcmeDemoBundle) by the real path.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class BundleFilter implements FilterInterface
{
    /**
     * @var array
     */
    private $bundles;

    /**
     * Constructor.
     *
     * @param array $bundles The map class of bundles
     */
    public function __construct(array $bundles)
    {
        $this->bundles = $bundles;
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
        $content = ContainerUtils::filterBundles($asset->getContent(), function ($matches) {
            $name = sprintf('%sBundle', $matches[1]);

            if (array_key_exists($name, $this->bundles)) {
                $ref = new \ReflectionClass($this->bundles[$name]);
                $dir = dirname($ref->getFileName());

                return str_replace('\\', '/', $dir);
            }

            return '';
        });

        $asset->setContent($content);
    }
}
