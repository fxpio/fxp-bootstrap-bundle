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
use Sonatra\Bundle\BlockBundle\Block\Extension\Core\Type\BlockType;
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
                'attr' => $attr,
                'popover_id' => $view->vars['id'],
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
            'popover' => array(),
        ));

        $resolver->addAllowedTypes('popover', 'array');

        $resolver->setNormalizer('popover', function (Options $options, $value) {
            $popoverResolver = new OptionsResolver();

            $popoverResolver->setDefaults(array(
                'toggle' => 'popover',
                'animation' => null,
                'html' => null,
                'placement' => null,
                'trigger' => null,
                'selector' => null,
                'title' => null,
                'content' => null,
                'delay' => null,
                'container' => null,
            ));

            $popoverResolver->setAllowedTypes('toggle', 'string');
            $popoverResolver->setAllowedTypes('animation', array('null', 'bool'));
            $popoverResolver->setAllowedTypes('html', array('null', 'bool'));
            $popoverResolver->setAllowedTypes('placement', array('null', 'string'));
            $popoverResolver->setAllowedTypes('selector', array('null', 'string', 'bool'));
            $popoverResolver->setAllowedTypes('trigger', array('null', 'string'));
            $popoverResolver->setAllowedTypes('title', array('null', 'string', '\Twig_Markup'));
            $popoverResolver->setAllowedTypes('content', array('null', 'string', '\Twig_Markup'));
            $popoverResolver->setAllowedTypes('delay', array('null', 'int'));
            $popoverResolver->setAllowedTypes('container', array('null', 'string', 'bool'));

            $popoverResolver->setAllowedValues('placement', array(null, 'top', 'bottom', 'left', 'right', 'auto', 'auto top', 'auto bottom', 'auto left', 'auto right'));

            return $popoverResolver->resolve($value);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return BlockType::class;
    }
}
