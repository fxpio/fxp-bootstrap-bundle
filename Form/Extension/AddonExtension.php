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
use Symfony\Component\OptionsResolver\Options;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;

/**
 * Addon Form Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class AddonExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $prepend = $options['prepend'];
        $append = $options['append'];

        // prepend
        if (is_string($prepend)) {
            $view->vars['prepend_string'] = $prepend;

        } elseif ($prepend instanceof FormInterface) {
            $view->vars['prepend_form'] = $prepend->createView($view);

        } elseif ($prepend instanceof BlockInterface) {
            $view->vars['prepend_block'] = $prepend->createView($this->createBlockView($view));
        }

        // append
        if (is_string($append)) {
            $view->vars['append_string'] = $append;

        } elseif ($append instanceof FormInterface) {
            $view->vars['append_form'] = $append->createView($view);

        } elseif ($append instanceof BlockInterface) {
            $view->vars['append_block'] = $append->createView($this->createBlockView($view));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'prepend' => null,
                'append'  => null,
            )
        );

        $resolver->addAllowedTypes(array(
            'prepend' => array('null', 'string', 'Symfony\Component\Form\FormInterface', 'Sonatra\Bundle\BlockBundle\Block\BlockInterface'),
            'append'  => array('null', 'string', 'Symfony\Component\Form\FormInterface', 'Sonatra\Bundle\BlockBundle\Block\BlockInterface'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }

    /**
     * Create the block view with the form view.
     *
     * @param FormView $view The form view
     *
     * @return BlockView
     */
    protected function createBlockView(FormView $view)
    {
        $bView = new BlockView();
        $bView->vars = $view->vars;
        $bView->vars['block'] = $bView;
        unset($bView->vars['form']);

        return $bView;
    }
}
