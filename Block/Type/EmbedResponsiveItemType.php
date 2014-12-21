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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Embed Responsive Item Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class EmbedResponsiveItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'item_type' => $options['type'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => true,
            'src'    => null,
            'type'   => 'iframe',
            'data'   => function (Options $options, $value) {
                if (isset($options['src'])) {
                    $value = $options['src'];
                }

                return $value;
            },
        ));

        $resolver->setAllowedTypes(array(
            'src'  => array('null', 'string'),
            'type' => 'string',
        ));

        $resolver->setAllowedValues(array(
            'type' => array('iframe', 'embed', 'object'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'embed_responsive_item';
    }
}
