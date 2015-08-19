<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Block\Extension;

use Sonatra\Bundle\BlockBundle\Block\AbstractTypeExtension;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Tooltip Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TooltipExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $tip = $options['tooltip'];

        if (null !== $tip['title']) {
            $attr = $options['attr'];

            foreach ($tip as $key => $value) {
                if (null !== $value) {
                    $attr[('title' === $key ? $key : 'data-'.$key)] = $value;
                }
            }

            $view->vars = array_replace($view->vars, array(
                'attr' => $attr,
                'tooltip_id' => $view->vars['id'],
                'render_id' => true,
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'tooltip' => array(),
        ));

        $resolver->addAllowedTypes(array(
            'tooltip' => 'array',
        ));

        $resolver->setNormalizer('tooltip', function (Options $options, $value) {
            $tooltipResolver = new OptionsResolver();

            $tooltipResolver->setDefaults(array(
                'toggle' => 'tooltip',
                'animation' => null,
                'html' => null,
                'placement' => null,
                'selector' => null,
                'title' => null,
                'trigger' => null,
                'delay' => null,
                'container' => null,
            ));

            $tooltipResolver->setAllowedTypes('toggle', 'string');
            $tooltipResolver->setAllowedTypes('animation', array('null', 'bool'));
            $tooltipResolver->setAllowedTypes('html', array('null', 'bool'));
            $tooltipResolver->setAllowedTypes('placement', array('null', 'string'));
            $tooltipResolver->setAllowedTypes('selector', array('null', 'string', 'bool'));
            $tooltipResolver->setAllowedTypes('title', array('null', 'string', '\Twig_Markup'));
            $tooltipResolver->setAllowedTypes('trigger', array('null', 'string'));
            $tooltipResolver->setAllowedTypes('delay', array('null', 'int'));
            $tooltipResolver->setAllowedTypes('container', array('null', 'string', 'bool'));

            $tooltipResolver->setAllowedValues('placement', array(null, 'top', 'bottom', 'left', 'right', 'auto', 'auto top', 'auto bottom', 'auto left', 'auto right'));

            return $tooltipResolver->resolve($value);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'block';
    }
}
