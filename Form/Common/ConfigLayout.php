<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Form\Common;

use Symfony\Component\Form\FormView;

/**
 * Common config for the layout.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigLayout
{
    /**
     * @param FormView $view
     */
    public static function finishView(FormView $view)
    {
        foreach ($view->children as $child) {
            $child->vars = array_replace($child->vars, array(
                'size' => $view->vars['size'],
                'layout' => $view->vars['layout'],
                'layout_col_size' => $view->vars['layout_col_size'],
                'layout_col_label' => $view->vars['layout_col_label'],
                'layout_col_control' => $view->vars['layout_col_control'],
                'validation_state' => $view->vars['validation_state'],
                'static_control' => $view->vars['static_control'],
                'static_control_empty' => $view->vars['static_control_empty'],
            ));
        }
    }
}
