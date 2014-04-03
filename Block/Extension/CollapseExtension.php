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
use Symfony\Component\OptionsResolver\Options;

/**
 * Collapse Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CollapseExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $attr = $view->vars['attr'];

        if ($options['collapsible']) {
            $class = isset($attr['class']) ? $attr['class'] : '';
            $class .= ' collapse';

            if ($options['collapse_in']) {
                $class .= ' in';
            }

            $attr['class'] = trim($class);

            if ('' === $attr['class']) {
                unset($attr['class']);
            }
        }

        $view->vars = array_replace($view->vars, array(
            'attr'        => $attr,
            'collapsible' => $options['collapsible'],
            'collapse_in' => $options['collapse_in'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'collapsible' => false,
            'collapse_in' => false,
            'render_id'   => function (Options $options) {
                return $options['collapsible'];
            },
        ));

        $resolver->addAllowedTypes(array(
            'collapsible' => 'bool',
            'collapse_in' => 'bool',
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
