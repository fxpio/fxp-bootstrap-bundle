<?php

/*
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
 * Panel Header Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelHeaderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $name => $child) {
            if (in_array('heading', $child->vars['block_prefixes'])) {
                $class = isset($child->vars['attr']['class']) ? $child->vars['attr']['class'] : '';
                $class .= ' panel-title';

                $child->vars['attr']['class'] = trim($class);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'panel_header';
    }
}
