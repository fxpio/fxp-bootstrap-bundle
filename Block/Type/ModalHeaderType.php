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
 * Modal Header Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ModalHeaderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'close_button' => $options['close_button'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $child) {
            if (in_array('heading', $child->vars['block_prefixes'])) {
                $class = isset($child->vars['attr']['class']) ? $child->vars['attr']['class'] : '';
                $class .= ' modal-title';

                $child->vars['attr']['class'] = trim($class);
                $child->vars['size'] = 4;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'close_button' => true,
        ));

        $resolver->setAllowedTypes('close_button', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'modal_header';
    }
}
