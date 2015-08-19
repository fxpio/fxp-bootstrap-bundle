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
use Sonatra\Bundle\BlockBundle\Block\BlockBuilderInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Pagination Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PaginationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if ($options['auto_pager']) {
            $builder->add('previous', 'pagination_item', $options['previous']);
            $builder->add('next', 'pagination_item', $options['next']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'size' => $options['size'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $name => $child) {
            if (in_array('pagination_item', $child->vars['block_prefixes'])) {
                if ('previous' === $name) {
                    $view->vars['block_previous'] = $child;
                    unset($view->children[$name]);
                } elseif ('next' === $name) {
                    $view->vars['block_next'] = $child;
                    unset($view->children[$name]);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'size'       => null,
            'auto_pager' => true,
            'previous'   => array(),
            'next'       => array(),
        ));

        $resolver->setAllowedTypes('size', array('null', 'string'));
        $resolver->setAllowedTypes('auto_pager', 'bool');
        $resolver->setAllowedTypes('previous', 'array');
        $resolver->setAllowedTypes('next', 'array');

        $resolver->setAllowedValues('size', array(null, 'sm', 'lg'));

        $resolver->setNormalizer('previous', function (Options $options, $value = null) {
            if (!isset($value['label'])) {
                $value['label'] = '&laquo;';
            }

            return $value;
        });
        $resolver->setNormalizer('next', function (Options $options, $value = null) {
            if (!isset($value['label'])) {
                $value['label'] = '&raquo;';
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pagination';
    }
}
