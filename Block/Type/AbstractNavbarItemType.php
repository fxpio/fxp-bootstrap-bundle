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
 * Abstract Class for Navbar Item Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class AbstractNavbarItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        if ($options['align']) {
            $class = isset($view->vars['attr']['class']) ? $view->vars['attr']['class'] : '';

            $view->vars['attr']['class'] = $class.' navbar-'.$options['align'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'chained_block' => true,
            'align' => null,
        ));

        $resolver->setAllowedTypes('align', array('null', 'string'));

        $resolver->setAllowedValues('align', array(null, 'left', 'right'));
    }
}
