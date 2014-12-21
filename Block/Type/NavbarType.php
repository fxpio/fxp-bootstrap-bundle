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
 * Navbar Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class NavbarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'style'    => $options['style'],
            'position' => $options['position'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        /* @var BlockView $header */
        $header = null;
        /* @var BlockView $collapse */
        $collapse = null;

        foreach ($view->children as $child) {
            if (null === $header && in_array('navbar_header', $child->vars['block_prefixes'])) {
                $header = $child;
            } elseif (null === $collapse && in_array('navbar_collapse', $child->vars['block_prefixes'])) {
                $collapse = $child;
            } elseif (in_array('container', $child->vars['block_prefixes'])) {
                foreach ($child->children as $subChild) {
                    if (null === $header && in_array('navbar_header', $subChild->vars['block_prefixes'])) {
                        $header = $subChild;
                    } elseif (null === $collapse && in_array('navbar_collapse', $subChild->vars['block_prefixes'])) {
                        $collapse = $subChild;
                    }
                }
            }
        }

        if (null !== $header && null !== $collapse) {
            $header->vars['collapse_id'] = $collapse->vars['id'];
        }

        $this->cleanChildren($view);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label'    => 'Toggle navigation',
            'style'    => 'default',
            'position' => null,
        ));

        $resolver->setAllowedTypes(array(
            'style'    => 'string',
            'position' => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'style'    => array('default', 'inverse'),
            'position' => array(null, 'static-top', 'fixed-top', 'fixed-bottom'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'navbar';
    }

    protected function cleanChildren(BlockView $view)
    {
        $class = isset($view->vars['attr']['class']) ? $view->vars['attr']['class'] : '';

        if (in_array('nav', $view->vars['block_prefixes'])) {
            $view->vars['attr']['class'] = 'navbar-nav '.$class;
            $view->vars['style'] = null;
        } elseif (in_array('nav_item', $view->vars['block_prefixes'])) {
            unset($view->vars['link_attr']['data-toggle']);
        } elseif (in_array('form', $view->vars['block_prefixes'])) {
            $view->vars['attr']['class'] = 'navbar-form '.$class;
            $view->vars['attr']['role'] = 'search';
        } elseif (in_array('button', $view->vars['block_prefixes'])) {
            $view->vars['attr']['class'] = $class.' navbar-button';
        } elseif (in_array('paragraph', $view->vars['block_prefixes']) || in_array('text', $view->vars['block_prefixes'])) {
            $view->vars['attr']['class'] = 'navbar-text '.$class;
        } elseif (in_array('link', $view->vars['block_prefixes'])) {
            $view->vars['attr']['class'] = 'navbar-link '.$class;
        }

        foreach ($view->children as $child) {
            $this->cleanChildren($child);
        }
    }
}
