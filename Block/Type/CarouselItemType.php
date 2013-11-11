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
 * Carousel Item Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CarouselItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {

        $view->vars = array_replace($view->vars, array(
            'active'  => $options['active'],
            'caption' => $options['caption'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'active'  => false,
            'caption' => null,
        ));

        $resolver->setAllowedTypes(array(
            'active'  => 'bool',
            'caption' => array('null', 'string'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'carousel_item';
    }
}
