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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Alert Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class AlertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'style' => $options['style'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'style' => 'info',
        ));

        $resolver->setAllowedTypes(array(
            'style' => 'string',
        ));

        $resolver->setAllowedValues(array(
            'style' => array('success', 'info', 'warning', 'danger'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'alert';
    }
}
