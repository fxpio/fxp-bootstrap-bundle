<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form Form Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FormExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'row_attr'             => $options['row_attr'],
            'display_label'        => $options['display_label'],
            'size'                 => $options['size'],
            'layout'               => $options['layout'],
            'layout_col_size'      => $options['layout_col_size'],
            'layout_col_label'     => $options['layout_col_label'],
            'layout_col_control'   => $options['layout_col_control'],
            'validation_state'     => $options['validation_state'],
            'static_control'       => $options['static_control'] && $options['disabled'],
            'static_control_empty' => $options['static_control_empty'],
            'help_text'            => $options['help_text'],
            'help_attr'            => $options['help_attr'],
        ));

        if (count($form->getErrors()) > 0) {
            $view->vars['validation_state'] = 'error';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (null !== $view->parent) {
            $view->vars = array_replace($view->vars, array(
                'layout'             => $view->parent->vars['layout'],
                'layout_col_size'    => $view->parent->vars['layout_col_size'],
                'layout_col_label'   => $view->parent->vars['layout_col_label'],
                'layout_col_control' => $view->parent->vars['layout_col_control'],
            ));

            if ('inline' === $view->vars['layout']) {
                $view->vars['display_label'] = false;
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
                'row_attr'             => array(),
                'display_label'        => true,
                'size'                 => null,
                'layout'               => null,
                'layout_col_size'      => 'lg', // only for horizontal layout
                'layout_col_label'     => 4, // only for horizontal layout
                'layout_col_control'   => 8, // only for horizontal layout
                'validation_state'     => null,
                'static_control'       => false, // renders P tag when this form is read only
                'static_control_empty' => 'null',
                'help_text'            => null,
                'help_attr'            => array(),
            )
        );

        $resolver->addAllowedTypes(array(
            'row_attr'             => array('array'),
            'display_label'        => array('bool'),
            'size'                 => array('null', 'string'),
            'layout'               => array('null', 'string'),
            'layout_col_size'      => array('string'),
            'layout_col_label'     => array('int'),
            'layout_col_control'   => array('int'),
            'validation_state'     => array('null', 'string'),
            'static_control'       => array('bool'),
            'static_control_empty' => array('null', 'string'),
            'help_text'            => array('null', 'string'),
            'help_attr'            => array('array'),
        ));

        $resolver->addAllowedValues(array(
            'size'             => array(null, 'sm', 'lg'),
            'layout'           => array(null, 'inline', 'horizontal'),
            'layout_col_size'  => array('xs', 'sm', 'md', 'lg'),
            'validation_state' => array(null, 'success', 'warning', 'error'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }
}
