<?php

/**
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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Image Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ImageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $attr = $options['attr'];
        $attr['alt'] = $options['alt'];

        if (null !== $view->vars['value']) {
            $attr['src'] = $view->vars['value'];
        }

        if (null !== $options['crossorigin']) {
            $attr['crossorigin'] = $options['crossorigin'];
        }

        if (null !== $options['height']) {
            $attr['height'] = $options['height'];
        }

        if (null !== $options['ismap']) {
            $attr['ismap'] = $options['ismap'];
        }

        if (null !== $options['usemap']) {
            $attr['usemap'] = $options['usemap'];
        }

        if (null !== $options['width']) {
            $attr['width'] = $options['width'];
        }

        $view->vars = array_replace($view->vars, array(
            'attr' => $attr,
            'style' => $options['style'],
            'responsive' => $options['responsive'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'mapped'      => true,
            'src'         => null,
            'alt'         => null,
            'style'       => null,
            'responsive'  => false,
            'crossorigin' => null,
            'height'      => null,
            'ismap'       => null,
            'usemap'      => null,
            'width'       => null,
        ));

        $resolver->setAllowedTypes(array(
            'src'         => array('null', 'string'),
            'alt'         => array('null', 'string'),
            'style'       => array('null', 'string'),
            'responsive'  => 'bool',
            'crossorigin' => array('null', 'string'),
            'height'      => array('null', 'string'),
            'ismap'       => array('null', 'string'),
            'usemap'      => array('null', 'string'),
            'width'       => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'style' => array('rounded', 'circle', 'thumbnail'),
        ));

        $resolver->setNormalizers(array(
            'data' => function (Options $options, $value) {
                if (isset($options['src'])) {
                    return $options['src'];
                }

                return $value;
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'image';
    }
}
