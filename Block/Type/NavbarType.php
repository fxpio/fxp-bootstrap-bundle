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
use Sonatra\Bundle\BlockBundle\Block\Util\BlockUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            'style' => $options['style'],
            'position' => $options['position'],
            'affix_style' => $options['affix_style'],
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'Toggle navigation',
            'style' => 'default',
            'position' => null,
            'affix_style' => false,
        ));

        $resolver->setAllowedTypes('style', 'string');
        $resolver->setAllowedTypes('position', array('null', 'string'));

        $resolver->setAllowedValues('style', array('default', 'inverse'));
        $resolver->setAllowedValues('position', array(null, 'static-top', 'fixed-top', 'fixed-bottom'));
        $resolver->setAllowedTypes('affix_style', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'navbar';
    }

    protected function cleanChildren(BlockView $view)
    {
        if (in_array('nav', $view->vars['block_prefixes'])) {
            BlockUtil::addAttributeClass($view, 'navbar-nav', true);
            $view->vars['style'] = null;
        } elseif (in_array('nav_item', $view->vars['block_prefixes'])) {
            unset($view->vars['link_attr']['data-toggle']);
        } elseif (in_array('form', $view->vars['block_prefixes'])) {
            BlockUtil::addAttributeClass($view, 'navbar-form', true);
            $view->vars['attr']['role'] = 'search';
        } elseif (in_array('button', $view->vars['block_prefixes'])) {
            BlockUtil::addAttributeClass($view, 'navbar-button');
        } elseif (in_array('paragraph', $view->vars['block_prefixes']) || in_array('text', $view->vars['block_prefixes'])) {
            BlockUtil::addAttributeClass($view, 'navbar-text', true);
        } elseif (in_array('link', $view->vars['block_prefixes'])) {
            BlockUtil::addAttributeClass($view, 'navbar-link', true);
        }

        foreach ($view->children as $child) {
            $this->cleanChildren($child);
        }
    }
}
