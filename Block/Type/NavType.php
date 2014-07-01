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
use Symfony\Component\OptionsResolver\Options;

/**
 * Nav Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class NavType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'style'    => $options['style'],
            'justifed' => $options['justifed'],
            'stacked'  => $options['stacked'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        $active = false;
        $firstItem = null;

        foreach ($view->children as $name => $child) {
            if (!in_array('nav_item', $child->vars['block_prefixes'])) {
                continue;
            }

            if (null === $firstItem) {
                $firstItem = $name;
            }

            if (isset($child->vars['active']) && $child->vars['active']) {
                $active = true;
                break;
            }
        }

        if (!$active && null !== $firstItem && $options['active_first']) {
            $view->children[$firstItem]->vars['active'] = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'style'        => 'tabs',
            'justifed'     => false,
            'stacked'      => false,
            'active_first' => true,
        ));

        $resolver->setAllowedTypes(array(
            'style'        => array('null', 'string'),
            'justifed'     => 'bool',
            'active_first' => 'bool',
        ));

        $resolver->setAllowedValues(array(
            'style' => array('tabs', 'pills'),
        ));

        $resolver->setNormalizers(array(
            'stacked' => function (Options $options, $value = null) {
                if ('tabs' === $options['style'] || null === $options['style']) {
                    return false;
                }

                return $value;
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'nav';
    }
}
