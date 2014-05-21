<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Block\Type;

use Sonatra\Bundle\BlockBundle\Block\AbstractType;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;

/**
 * Breadcrumb Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class BreadcrumbType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        /* @var BlockView $last */
        $last = null;

        foreach ($view->children as $child) {
            if (in_array('breadcrumb_item', $child->vars['block_prefixes'])) {
                $last = $child;
            }
        }

        if (null !== $last) {
            $last->vars['active'] = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'breadcrumb';
    }
}
