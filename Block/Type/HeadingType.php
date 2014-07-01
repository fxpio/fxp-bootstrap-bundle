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
 * Heading Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class HeadingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'size'      => $options['size'],
            'secondary' => $options['secondary'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'size'      => 1,
            'secondary' => null,
        ));

        $resolver->setAllowedTypes(array(
            'size'      => 'int',
            'secondary' => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'size' => array(1, 2, 3, 4, 5, 6),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'heading';
    }
}
