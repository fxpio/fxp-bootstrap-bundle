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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Nav Item Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class NavItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $linkAttr = $options['link_attr'];

        if (null !== $options['src']) {
            $linkAttr['href'] = $options['src'];
        }

        $view->vars = array_replace($view->vars, array(
            'link_attr' => $linkAttr,
            'active' => $options['active'],
            'disabled' => $options['disabled'],
            'rendered' => $options['rendered'],
            'style' => 'tabs',
        ));

        if (isset($view->parent->vars['style'])) {
            $view->vars['style'] = $view->parent->vars['style'];
        }

        if ('tabs' === $view->vars['style']) {
            $view->vars['link_attr']['data-toggle'] = 'tab';
        } elseif ('pills' === $view->vars['style']) {
            $view->vars['link_attr']['data-toggle'] = 'pill';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars['dropup'] = $options['dropup'];
        $view->vars['is_item_dropdown'] = false;

        foreach ($view->children as $name => $child) {
            if (in_array('dropdown', $child->vars['block_prefixes'])) {
                $child->vars['wrapper'] = false;
                $view->vars['is_item_dropdown'] = true;
                $view->vars['dropdown'] = $child;
                unset($view->children[$name]);

                foreach ($child->children as $sChild) {
                    if (isset($child->parent->vars['link_attr']['data-toggle']) && !in_array('dropdown', $child->parent->vars['block_prefixes'])) {
                        $sChild->vars['link_attr']['data-toggle'] = $child->parent->vars['link_attr']['data-toggle'];
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'src' => '#',
            'link_attr' => array(),
            'active' => false,
            'disabled' => false,
            'chained_block' => true,
            'dropup' => false,
            'rendered' => true,
        ));

        $resolver->setAllowedTypes('src', array('null', 'string'));
        $resolver->setAllowedTypes('link_attr', 'array');
        $resolver->setAllowedTypes('active', 'bool');
        $resolver->setAllowedTypes('disabled', 'bool');
        $resolver->setAllowedTypes('dropup', 'bool');
        $resolver->setAllowedTypes('rendered', 'bool');

        $resolver->setNormalizer('src', function (Options $options, $value = null) {
            if (isset($options['data'])) {
                return $options['data'];
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'nav_item';
    }
}
