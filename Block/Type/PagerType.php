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
 * Pager Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PagerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'previous_label'    => $options['previous_label'],
            'previous_attr'     => $options['previous_attr'],
            'previous_disabled' => $options['previous_disabled'],
            'previous_src'      => $options['previous_src'],
            'next_label'        => $options['next_label'],
            'next_attr'         => $options['next_attr'],
            'next_disabled'     => $options['next_disabled'],
            'next_src'          => $options['next_src'],
            'aligned'           => $options['aligned'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'previous_label'    => 'Previsous',
            'previous_attr'     => array(),
            'previous_disabled' => false,
            'previous_src'      => '#',
            'next_label'        => 'Next',
            'next_attr'         => array(),
            'next_disabled'     => false,
            'next_src'          => '#',
            'aligned'           => false,
        ));

        $resolver->setAllowedTypes('previous_label', 'string');
        $resolver->setAllowedTypes('previous_attr', 'array');
        $resolver->setAllowedTypes('previous_disabled', 'bool');
        $resolver->setAllowedTypes('previous_src', 'string');
        $resolver->setAllowedTypes('next_label', 'string');
        $resolver->setAllowedTypes('next_attr', 'array');
        $resolver->setAllowedTypes('next_disabled', 'bool');
        $resolver->setAllowedTypes('next_src', 'string');
        $resolver->setAllowedTypes('aligned', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pager';
    }
}
