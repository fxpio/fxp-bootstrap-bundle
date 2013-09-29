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
use Sonatra\Bundle\BootstrapBundle\Assetic\Util\PathUtils;

/**
 * Convert the target (file or directory) to the relative path since a
 * asset directory.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PathRelativeFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function filterLoad(AssetInterface $asset)
    {
        $content = PathUtils::filterRelative($asset->getContent(), function($matches) use ($asset) {
            $value = PathUtils::convertToRelative($asset, $matches['relative']);

            return str_replace($matches[0], $value, $matches[0]);
        });

        $asset->setContent($content);
    }

    /**
     * {@inheritdoc}
     */
    public function filterDump(AssetInterface $asset)
    {
    }
}
