<?php

/**
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
     * @var string
     */
    private $name;

    /**
     * Constructor.
     *
     * @param AsseticNode    $node
     * @param AssetInterface $asset
     * @param string         $name
     */
    public function __construct(AsseticNode $node, AssetInterface $asset, $name)
    {
        $this->node = $node;
        $this->asset = $asset;
        $this->name = $name;
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
