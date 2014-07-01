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
 * Media Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'media_list' => false,
            'tag'        => 'div',
        ));

        if (isset($view->parent->vars['media_list']) && $view->parent->vars['media_list']) {
            $view->vars = array_replace($view->vars, array(
                'media_list' => true,
                'tag'        => 'li',
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $child) {
            if (in_array('link', $child->vars['block_prefixes']) || in_array('image', $child->vars['block_prefixes'])) {
                $class = isset($child->vars['attr']['class']) ? $child->vars['attr']['class'] : '';

                if (in_array('image', $child->vars['block_prefixes'])) {
                    $class .= ' media-object';
                }

                $class .= ' pull-' . $options['align'];
                $class = trim($class);

                $child->vars['attr']['class'] = $class;

                foreach ($child->children as $subChild) {
                    if (in_array('image', $subChild->vars['block_prefixes'])) {
                        $subClass = isset($subChild->vars['attr']['class']) ? $subChild->vars['attr']['class'] : '';

                        $subClass .= ' media-object';
                        $subClass = trim($subClass);

                        $subChild->vars['attr']['class'] = $subClass;
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'align' => 'left',
        ));

        $resolver->setAllowedTypes(array(
            'align' => 'string',
        ));

        $resolver->setAllowedValues(array(
            'align' => array('left', 'right'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'media';
    }
}
