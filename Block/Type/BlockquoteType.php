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
 * Blockquote Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class BlockquoteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'pull' => $options['pull'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'pull' => null,
        ));

        $resolver->setAllowedTypes(array(
            'pull' => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'pull' => array('left', 'right'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'blockquote';
    }
}
