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
use Sonatra\Bundle\BootstrapBundle\Block\Type\ButtonType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Button Collapse Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ButtonCollapseExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if (null !== $options['collapse_id']) {
            $view->vars = array_replace($view->vars, array(
                'attr' => array_replace($view->vars['attr'], array(
                    'data-toggle' => 'collapse',
                    'data-target' => sprintf('#%s', trim($options['collapse_id'], '#')),
                )),
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'collapse_id' => null,
        ));

        $resolver->addAllowedTypes('collapse_id', array('null', 'string'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ButtonType::class;
    }
}
