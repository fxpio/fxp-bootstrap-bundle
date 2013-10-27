<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Block\Extension;

use Sonatra\Bundle\BlockBundle\Block\AbstractTypeExtension;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;

/**
 * Repeated Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class RepeatedExtension extends AbstractTypeExtension
{
     /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $child) {
            $child->vars = array_replace($child->vars, array(
                'layout'             => $view->vars['layout'],
                'layout_col_size'    => $view->vars['layout_col_size'],
                'layout_col_label'   => $view->vars['layout_col_label'],
                'layout_col_control' => $view->vars['layout_col_control'],
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'repeated';
    }
}
