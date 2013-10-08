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
use Symfony\Component\Form\Exception\InvalidConfigurationException;

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
            'row_attr'      => $options['row_attr'],
            'display_label' => $options['display_label'],
            'layout'        => $options['layout'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (null !== $options['layout']) {
            if (null !== $view->parent) {
                throw new InvalidConfigurationException('The layout option can be specified only that in the root of form');
            }

            // inline
            if ('inline' === $options['layout']) {
                foreach ($view->children as $child) {
                    $child->vars['display_label'] = false;
                }
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
                'row_attr'      => array(),
                'display_label' => true,
                'layout'        => null,
            )
        );

        $resolver->addAllowedTypes(array(
            'row_attr'      => array('array'),
            'display_label' => array('bool'),
            'layout'        => array('null', 'string'),
        ));

        $resolver->addAllowedValues(array(
            'layout'        => array('inline'),
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
