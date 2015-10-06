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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Button Form Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ButtonExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (null !== $options['glyphicon']) {
            $view->vars['glyphicon'] = $options['glyphicon'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $class = '';

        if (isset($view->vars['attr']['class'])) {
            $class = $view->vars['attr']['class'];
        }

        if ($options['block_level']) {
            $class = 'btn-block '.$class;
        }

        if (null !== $options['style']) {
            $class = 'btn-'.$options['style'].' '.$class;
        }

        if (null !== $options['size']) {
            $class = 'btn-'.$options['size'].' '.$class;
        }

        $view->vars['attr']['class'] = trim('btn '.$class);

        // layout
        if (null !== $view->parent && isset($view->parent->vars['layout'])) {
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
        $resolver->setDefaults(array(
            'style' => null,
            'size' => null,
            'block_level' => false,
            'glyphicon' => null,
        ));

        $resolver->addAllowedTypes('style', array('null', 'string'));
        $resolver->addAllowedTypes('size', array('null', 'string'));
        $resolver->addAllowedTypes('block_level', 'bool');

        $resolver->addAllowedValues('style', array(null, 'default', 'primary', 'success', 'info', 'warning', 'danger', 'link'));
        $resolver->addAllowedValues('size', array(null, 'xs', 'sm', 'lg'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'button';
    }
}
