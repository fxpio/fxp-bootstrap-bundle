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
 * Dropdown Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class DropdownType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'wrapper'      => $options['wrapper'],
            'wrapper_attr' => $options['wrapper_attr'],
            'pull_right'   => $options['pull_right'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $name => $child) {
            if (in_array('dropdown_header', $child->vars['block_prefixes'])) {
                $child->vars['divider'] = false;
            }

            break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'wrapper'      => true,
            'wrapper_attr' => array(),
            'pull_right'   => true,
        ));

        $resolver->setAllowedTypes(array(
            'wrapper'      => 'bool',
            'wrapper_attr' => 'array',
            'pull_right'   => 'bool',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'dropdown';
    }
}
