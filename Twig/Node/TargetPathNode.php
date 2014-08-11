<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Twig\Node;

use Assetic\Asset\AssetInterface;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TargetPathNode extends AsseticNode
{
    /**
     * @var AsseticNode
     */
    private $node;

    /**
     * @var AssetInterface
     */
    private $asset;

    /**
     * Constructor.
     *
     * @param AsseticNode    $node
     * @param AssetInterface $asset
     */
    public function __construct(AsseticNode $node, AssetInterface $asset)
    {
        $this->node = $node;
        $this->asset = $asset;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->repr($this->asset->getTargetPath());
    }

    /**
     * {@inheritdoc}
     */
    public function getLine()
    {
        return $this->node->getLine();
    }
}
