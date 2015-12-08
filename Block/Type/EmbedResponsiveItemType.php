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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => true,
            'src' => null,
            'type' => 'iframe',
            'data' => function (Options $options, $value) {
                if (isset($options['src'])) {
                    $value = $options['src'];
                }

                return $value;
            },
        ));

        $resolver->setAllowedTypes('src', array('null', 'string'));
        $resolver->setAllowedTypes('type', 'string');

        $resolver->setAllowedValues('type', array('iframe', 'embed', 'object'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'embed_responsive_item';
    }
}
