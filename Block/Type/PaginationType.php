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
use Sonatra\Bundle\BlockBundle\Block\BlockFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

/**
 * Pagination Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PaginationType extends AbstractType
{
    /**
     * @var BlockFactoryInterface
     */
    protected $factory;

    /**
     * Constructor.
     *
     * @param BlockFactoryInterface $factory
     */
    public function __construct(BlockFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if ($options['auto_pager']) {
            $blockPrevious = $this->factory->createNamed('previous', 'pagination_item', null, $options['previous']);
            $blockNext = $this->factory->createNamed('next', 'pagination_item', null, $options['next']);

            $builder->setAttribute('block_previous', $blockPrevious);
            $builder->setAttribute('block_next', $blockNext);
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

        if ($options['auto_pager']) {
            $view->vars = array_replace($view->vars, array(
                'block_previous' => $block->getConfig()->getAttribute('block_previous')->createView($view),
                'block_next'     => $block->getConfig()->getAttribute('block_next')->createView($view),
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'size'       => null,
            'auto_pager' => true,
            'previous'   => array(),
            'next'       => array(),
        ));

        $resolver->setAllowedTypes(array(
            'size'       => array('null', 'string'),
            'auto_pager' => 'bool',
            'previous'   => 'array',
            'next'       => 'array',
        ));

        $resolver->setAllowedValues(array(
            'size' => array('sm', 'lg'),
        ));

        $resolver->setNormalizers(array(
            'previous' => function (Options $options, $value = null) {
                if (!isset($value['label'])) {
                    $value['label'] = '&laquo;';
                }

                return $value;
            },
            'next' => function (Options $options, $value = null) {
                if (!isset($value['label'])) {
                    $value['label'] = '&raquo;';
                }

                return $value;
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pagination';
    }
}
