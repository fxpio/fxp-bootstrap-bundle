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
 * Carousel Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CarouselType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $attr = $view->vars['attr'];
        $attr['data-ride'] = 'carousel';

        if (null !== $options['interval']) {
            $attr['data-interval'] = $options['interval'];
        }

        if (null !== $options['pause']) {
            $attr['data-pause'] = $options['pause'];
        }

        if (null !== $options['wrap']) {
            $attr['data-wrap'] = $options['wrap'];
        }

        if (null !== $options['slide']) {
            $attr['data-slide'] = $options['slide'];
        }

        if (null !== $options['slide_to']) {
            $attr['data-slide-to'] = $options['slide_to'];
        }

        $view->vars = array_replace($view->vars, array(
            'attr'    => $attr,
            'control' => $options['control'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        if ($options['indicator']) {
            $indicators = array();
            $hasActive = false;
            /* @var BlockView $firstChild */
            $firstChild = null;

            foreach ($view->children as $child) {
                if (in_array('carousel_item', $child->vars['block_prefixes'])) {
                    $active = isset($child->vars['active']) && $child->vars['active'];
                    $indicators[] = $active;

                    if (null === $firstChild) {
                        $firstChild = $child;
                    }

                    if ($active) {
                        if ($hasActive) {
                            $child->vars['active'] = false;
                        }

                        $hasActive = true;
                    }
                }
            }

            if (!$hasActive && count($indicators) > 0) {
                $indicators[0] = true;
                $firstChild->vars['active'] = true;
            }

            $view->vars = array_replace($view->vars, array(
                'indicators' => $indicators,
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'render_id' => true,
            'control'   => true,
            'indicator' => true,
            'interval'  => null,
            'pause'     => null,
            'wrap'      => null,
            'slide'     => null,
            'slide_to'  => null,
        ));

        $resolver->setAllowedTypes(array(
            'control'   => 'bool',
            'indicator' => 'bool',
            'interval'  => array('null', 'int'),
            'pause'     => array('null', 'string'),
            'wrap'      => array('null', 'bool'),
            'slide'     => array('null', 'string'),
            'slide_to'  => array('null', 'int'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'carousel';
    }
}
