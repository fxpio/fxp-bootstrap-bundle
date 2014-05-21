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
 * Link Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LinkType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $attr = $options['attr'];

        if (null !== $options['src']) {
            $attr['href'] = $options['src'];
        }

        if (null !== $options['alt']) {
            $attr['alt'] = $options['alt'];
        }

        if (null !== $options['target']) {
            $attr['target'] = $options['target'];
        }

        if (null !== $options['ping']) {
            $attr['ping'] = $options['ping'];
        }

        if (null !== $options['rel']) {
            $attr['rel'] = $options['rel'];
        }

        if (null !== $options['media']) {
            $attr['media'] = $options['media'];
        }

        if (null !== $options['hreflang']) {
            $attr['hreflang'] = $options['hreflang'];
        }

        if (null !== $options['type']) {
            $attr['type'] = $options['type'];
        }

        $view->vars = array_replace($view->vars, array(
            'attr'  => $attr,
            'style' => $options['style'],
            'size'  => $options['size'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'src'      => null,
            'alt'      => null,
            'target'   => null,
            'ping'     => null,
            'rel'      => null,
            'media'    => null,
            'hreflang' => null,
            'type'     => null,
            'style'    => 'link',
            'size'     => null,
        ));

        $resolver->setAllowedTypes(array(
            'src'      => array('null', 'string'),
            'alt'      => array('null', 'string'),
            'target'   => array('null', 'string'),
            'ping'     => array('null', 'string'),
            'rel'      => array('null', 'string'),
            'media'    => array('null', 'string'),
            'hreflang' => array('null', 'string'),
            'type'     => array('null', 'string'),
            'style'    => 'string',
            'size'     => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'target' => array('null', '_blank', '_self', '_top', '_parent'),
            'rel'    => array('alternate', 'author', 'help', 'license', 'next', 'nofollow', 'noreferrer', 'prefetch', 'prev', 'search', 'tag'),
            'style'  => array('default', 'primary', 'success', 'info', 'warning', 'danger', 'link'),
            'size'  => array('xs', 'sm', 'lg'),
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
        return 'link';
    }
}
