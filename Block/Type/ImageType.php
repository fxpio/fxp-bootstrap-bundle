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
use Sonatra\Bundle\BlockBundle\Block\BlockBuilderInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonatra\Bundle\BlockBundle\Block\Extension\Core\DataMapper\WrapperMapper;

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
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper(new WrapperMapper());
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $attr = $options['attr'];
        $attr['alt'] = $options['alt'];

        if (null !== $options['src']) {
            $attr['src'] = $options['src'];
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
            'src'           => null,
            'alt'           => null,
            'style'         => null,
            'responsive'    => false,
            'crossorigin'   => null,
            'height'        => null,
            'ismap'         => null,
            'usemap'        => null,
            'width'         => null,
            'display_label' => false,
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
            'src' => function (Options $options, $value = null) {
                if (isset($options['data'])) {
                    return $options['data'];
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