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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Button Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ButtonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'tag'      => $options['tag'],
            'disabled' => $options['disabled'],
            'src'      => $options['src'],
            'prepend'  => $options['prepend'],
            'append'   => $options['append'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        $class = '';

        if (isset($view->vars['attr']['class'])) {
            $class = $view->vars['attr']['class'];
        }

        if ($options['block_level']) {
            $class = 'btn-block ' . $class;
        }

        if (null !== $options['style']) {
            $class = 'btn-' . $options['style'] . ' ' . $class;
        }

        if (null !== $options['size']) {
            $class = 'btn-' . $options['size'] . ' ' . $class;
        }

        $view->vars['attr']['class'] = trim('btn ' . $class);
        $view->vars['prepend_is_string'] = true;
        $view->vars['append_is_string'] = true;

        // layout
        if (null !== $view->parent && isset($view->parent->vars['layout'])) {
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

        foreach ($view->children as $name => $child) {
            if ('prepend' === $name) {
                $view->vars['prepend'] = $child;
                $view->vars['prepend_is_string'] = false;
                unset($view->children[$name]);

            } elseif ('append' === $name) {
                $view->vars['append'] = $child;
                $view->vars['append_is_string'] = false;
                unset($view->children[$name]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'tag'         => 'button',
            'disabled'    => false,
            'src'         => null,
            'style'       => null,
            'size'        => null,
            'block_level' => false,
            'prepend'     => null,
            'append'      => null,
        ));

        $resolver->setAllowedTypes(array(
            'tag'         => 'string',
            'src'         => array('null', 'string'),
            'style'       => array('null', 'string'),
            'size'        => array('null', 'string'),
            'block_level' => 'bool',
            'prepend'     => array('null', 'string'),
            'append'      => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'tag'   => array('button', 'a'),
            'style' => array('default', 'primary', 'success', 'info', 'warning', 'danger', 'link'),
            'size'  => array('xs', 'sm', 'lg'),
        ));

        $resolver->setNormalizers(array(
            'src' => function (Options $options, $value = null) {
                if (isset($options['data'])) {
                    return $options['data'];
                }

                return $value;
            },
            'tag' => function (Options $options, $value = null) {
                if (isset($options['data']) || isset($options['src'])) {
                    return 'a';
                }

                return $value;
            }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'button';
    }
}
