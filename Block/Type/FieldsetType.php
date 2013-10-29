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
        $view->vars = array_replace($view->vars, array(
            'legend'      => $options['legend'],
            'legend_attr' => $options['legend_attr'],
            'compound'    => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $child) {
            $child->vars = array_replace($child->vars, array(
                'layout'               => $view->vars['layout'],
                'layout_col_size'      => $view->vars['layout_col_size'],
                'layout_col_label'     => $view->vars['layout_col_label'],
                'layout_col_control'   => $view->vars['layout_col_control'],
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'legend'       => null,
            'legend_attr'  => array(),
            'compound'     => true,
            'inherit_data' => true,
        ));

        $resolver->setAllowedTypes(array(
            'legend'      => array('null', 'string'),
            'legend_attr' => array('array'),
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
