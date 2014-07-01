<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Fiedlset Form Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FieldsetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'legend'      => $options['legend'],
            'legend_attr' => $options['legend_attr'],
            'compound'    => true,
            'required'    => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view->children as $child) {
            $child->vars = array_replace($child->vars, array(
                'size'                 => $view->vars['size'],
                'layout'               => $view->vars['layout'],
                'layout_col_size'      => $view->vars['layout_col_size'],
                'layout_col_label'     => $view->vars['layout_col_label'],
                'layout_col_control'   => $view->vars['layout_col_control'],
                'validation_state'     => $view->vars['validation_state'],
                'static_control'       => $view->vars['static_control'],
                'static_control_empty' => $view->vars['static_control_empty'],
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
