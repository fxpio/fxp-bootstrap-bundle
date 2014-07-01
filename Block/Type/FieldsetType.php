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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Fiedlset Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FieldsetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if (null !== $view->parent) {
            $view->vars = array_replace($view->vars, array(
                'row'       => $view->parent->vars['row'],
                'row_label' => $view->parent->vars['row_label'],
            ));

            if (in_array('object', $view->parent->vars['block_prefixes'])) {
                $view->vars = array_replace($view->vars, array(
                    'layout'             => $view->parent->vars['layout'],
                    'layout_col_size'    => $view->parent->vars['layout_col_size'],
                    'layout_col_label'   => $view->parent->vars['layout_col_label'],
                    'layout_col_control' => $view->parent->vars['layout_col_control'],
                ));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        if (null !== $view->parent && in_array('object', $view->parent->vars['block_prefixes'])) {
            foreach ($view->children as $child) {
                $child->vars = array_replace($child->vars, array(
                    'layout'             => $view->parent->vars['layout'],
                    'layout_col_size'    => $view->parent->vars['layout_col_size'],
                    'layout_col_label'   => $view->parent->vars['layout_col_label'],
                    'layout_col_control' => $view->parent->vars['layout_col_control'],
                ));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'fieldset';
    }
}
