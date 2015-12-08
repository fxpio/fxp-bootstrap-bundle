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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * List Group Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ListGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'tag' => $options['tag'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $child) {
            if (in_array('list_group_item', $child->vars['block_prefixes']) && $child->vars['is_link']) {
                $view->vars = array_replace($view->vars, array(
                    'tag' => 'div',
                ));

                break;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'tag' => 'ul',
        ));

        $resolver->setAllowedTypes('tag', 'string');

        $resolver->setAllowedValues('tag', array('ul', 'div'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'list_group';
    }
}
