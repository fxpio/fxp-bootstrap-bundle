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
 * Paragraph Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ParagraphType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'lead'     => $options['lead'],
            'align'    => $options['align'],
            'emphasis' => $options['emphasis'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'lead'     => false,
            'align'    => null,
            'emphasis' => null,
        ));

        $resolver->setAllowedTypes(array(
            'lead'     => 'bool',
            'align'    => array('null', 'string'),
            'emphasis' => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'align'    => array('left', 'center', 'right'),
            'emphasis' => array('muted', 'primary', 'success', 'info', 'warning', 'danger'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'paragraph';
    }
}
