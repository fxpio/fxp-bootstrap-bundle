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
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            'row_attr' => $options['row_attr'],
            'display_label' => $options['display_label'],
            'size' => $options['size'],
            'layout' => $options['layout'],
            'layout_col_size' => $options['layout_col_size'],
            'layout_col_label' => $options['layout_col_label'],
            'layout_col_control' => $options['layout_col_control'],
            'validation_state' => $options['validation_state'],
            'static_control' => $options['static_control'] && $options['disabled'],
            'static_control_empty' => $options['static_control_empty'],
            'help_text' => $options['help_text'],
            'help_attr' => $options['help_attr'],
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
                'layout' => $view->parent->vars['layout'],
                'layout_col_size' => $view->parent->vars['layout_col_size'],
                'layout_col_label' => $view->parent->vars['layout_col_label'],
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'row_attr' => array(),
                'display_label' => true,
                'size' => null,
                'layout' => null,
                'layout_col_size' => 'lg', // only for horizontal layout
                'layout_col_label' => 4, // only for horizontal layout
                'layout_col_control' => 8, // only for horizontal layout
                'validation_state' => null,
                'static_control' => false, // renders P tag when this form is read only
                'static_control_empty' => 'null',
                'help_text' => null,
                'help_attr' => array(),
            )
        );

        $resolver->addAllowedTypes('row_attr', 'array');
        $resolver->addAllowedTypes('display_label', 'bool');
        $resolver->addAllowedTypes('size', array('null', 'string'));
        $resolver->addAllowedTypes('layout', array('null', 'string'));
        $resolver->addAllowedTypes('layout_col_size', 'string');
        $resolver->addAllowedTypes('layout_col_label', 'int');
        $resolver->addAllowedTypes('layout_col_control', 'int');
        $resolver->addAllowedTypes('validation_state', array('null', 'string'));
        $resolver->addAllowedTypes('static_control', 'bool');
        $resolver->addAllowedTypes('static_control_empty', array('null', 'string'));
        $resolver->addAllowedTypes('help_text', array('null', 'string'));
        $resolver->addAllowedTypes('help_attr', 'array');

        $resolver->addAllowedValues('size', array(null, 'sm', 'lg'));
        $resolver->addAllowedValues('layout', array(null, 'inline', 'horizontal'));
        $resolver->addAllowedValues('layout_col_size', array('xs', 'sm', 'md', 'lg'));
        $resolver->addAllowedValues('validation_state', array(null, 'success', 'warning', 'error'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}
