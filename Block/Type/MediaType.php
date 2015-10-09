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
            'tag' => 'div',
        ));

        if (isset($view->parent->vars['media_list']) && $view->parent->vars['media_list']) {
            $view->vars = array_replace($view->vars, array(
                'media_list' => true,
                'tag' => 'li',
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
                if (in_array('image', $child->vars['block_prefixes'])) {
                    BlockUtil::addAttributeClass($view, 'media-object');
                }

                BlockUtil::addAttributeClass($view, 'pull-'.$options['align']);

                foreach ($child->children as $subChild) {
                    if (in_array('image', $subChild->vars['block_prefixes'])) {
                        BlockUtil::addAttributeClass($subChild, 'media-object');
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
            'align' => 'left',
        ));

        $resolver->setAllowedTypes('align', 'string');

        $resolver->setAllowedValues('align', array('left', 'right'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'media';
    }
}
