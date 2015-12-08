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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => true,
            'src' => null,
            'alt' => null,
            'style' => null,
            'responsive' => false,
            'crossorigin' => null,
            'height' => null,
            'ismap' => null,
            'usemap' => null,
            'width' => null,
        ));

        $resolver->setAllowedTypes('src', array('null', 'string'));
        $resolver->setAllowedTypes('alt', array('null', 'string'));
        $resolver->setAllowedTypes('style', array('null', 'string'));
        $resolver->setAllowedTypes('responsive', 'bool');
        $resolver->setAllowedTypes('crossorigin', array('null', 'string'));
        $resolver->setAllowedTypes('height', array('null', 'string'));
        $resolver->setAllowedTypes('ismap', array('null', 'string'));
        $resolver->setAllowedTypes('usemap', array('null', 'string'));
        $resolver->setAllowedTypes('width', array('null', 'string'));

        $resolver->setAllowedValues('style', array(null, 'rounded', 'circle', 'thumbnail'));

        $resolver->setNormalizer('data', function (Options $options, $value) {
            if (isset($options['src'])) {
                return $options['src'];
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'image';
    }
}
