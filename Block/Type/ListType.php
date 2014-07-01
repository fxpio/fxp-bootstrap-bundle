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
 * List Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $tag = 'ul';

        if ('ordered' === $options['style']) {
            $tag = 'ol';

        } elseif ('description' === $options['style']) {
            $tag = 'dl';
        }

        $view->vars = array_replace($view->vars, array(
            'style'      => $options['style'],
            'tag'        => $tag,
            'unstyled'   => $options['unstyled'],
            'inline'     => $options['inline'],
            'horizontal' => $options['horizontal'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'style'      => 'unordered',
            'unstyled'   => false,
            'inline'     => false,
            'horizontal' => false,
        ));

        $resolver->setAllowedTypes(array(
            'style'      => 'string',
            'unstyled'   => 'bool',
            'inline'     => 'bool',
            'horizontal' => 'bool',
        ));

        $resolver->setAllowedValues(array(
            'style' => array('unordered', 'ordered', 'description'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'list';
    }
}
