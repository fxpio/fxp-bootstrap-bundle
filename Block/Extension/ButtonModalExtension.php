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

/**
 * Button Modal Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ButtonModalExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if (null !== $options['modal_id']) {
            $view->vars = array_replace($view->vars, array(
                'attr' => array_replace($view->vars['attr'], array(
                    'data-toggle' => 'modal',
                    'data-target' => sprintf('#%s', trim($options['modal_id'], '#')),
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
            'modal_id' => null,
        ));

        $resolver->addAllowedTypes(array(
            'modal_id' => array('null', 'string'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'button';
    }
}
