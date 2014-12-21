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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Layout Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LayoutExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'layout'             => $options['layout'],
            'layout_col_size'    => $options['layout_col_size'],
            'layout_col_label'   => $options['layout_col_label'],
            'layout_col_control' => $options['layout_col_control'],
        ));
    }

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

            if ('inline' === $view->vars['layout']) {
                $child->vars['display_label'] = false;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'layout'             => null,
                'layout_col_size'    => 'lg', // only for horizontal layout
                'layout_col_label'   => 4, // only for horizontal layout
                'layout_col_control' => 8, // only for horizontal layout
            )
        );

        $resolver->addAllowedTypes(array(
            'layout'             => array('null', 'string'),
            'layout_col_size'    => array('string'),
            'layout_col_label'   => array('int'),
            'layout_col_control' => array('int'),
        ));

        $resolver->addAllowedValues(array(
            'layout'          => array(null, 'inline', 'horizontal'),
            'layout_col_size' => array('xs', 'sm', 'md', 'lg'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'object';
    }
}
