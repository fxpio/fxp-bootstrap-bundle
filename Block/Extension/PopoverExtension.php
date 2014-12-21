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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Popover Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PopoverExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $tip = $options['popover'];

        if (null !== $tip['content']) {
            $attr = $options['attr'];

            foreach ($tip as $key => $value) {
                if (null !== $value) {
                    $attr['data-'.$key] = $value;
                }
            }

            if (null !== $view->parent && in_array('button_group', $view->parent->vars['block_prefixes'])) {
                $attr['data-container'] = 'body';
            }

            $view->vars = array_replace($view->vars, array(
                'attr'       => $attr,
                'popover_id' => $view->vars['id'],
                'render_id'  => true,
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'popover' => array(),
        ));

        $resolver->addAllowedTypes(array(
            'popover' => 'array',
        ));

        $resolver->setNormalizers(array(
            'popover' => function (Options $options, $value) {
                $popoverResolver = new OptionsResolver();

                $popoverResolver->setDefaults(array(
                    'toggle'    => 'popover',
                    'animation' => null,
                    'html'      => null,
                    'placement' => null,
                    'trigger'   => null,
                    'selector'  => null,
                    'title'     => null,
                    'content'   => null,
                    'delay'     => null,
                    'container' => null,
                ));

                $popoverResolver->setAllowedTypes(array(
                    'toggle'    => 'string',
                    'animation' => array('null', 'bool'),
                    'html'      => array('null', 'bool'),
                    'placement' => array('null', 'string'),
                    'selector'  => array('null', 'string', 'bool'),
                    'trigger'   => array('null', 'string'),
                    'title'     => array('null', 'string', '\Twig_Markup'),
                    'content'   => array('null', 'string', '\Twig_Markup'),
                    'delay'     => array('null', 'int'),
                    'container' => array('null', 'string', 'bool'),
                ));

                $popoverResolver->setAllowedValues(array(
                    'placement' => array(null, 'top', 'bottom', 'left', 'right', 'auto', 'auto top', 'auto bottom', 'auto left', 'auto right'),
                ));

                return $popoverResolver->resolve($value);
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'block';
    }
}
