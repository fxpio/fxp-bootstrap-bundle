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

/**
 * Modal Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ModalType extends AbstractType
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
        if (!empty($options['label'])) {
            $blockHeader = $this->factory->createNamed('header', 'modal_header', null, array('label' => $options['label']));

            $builder->setAttribute('block_header', $blockHeader);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'transition'   => $options['transition'],
            'dialog_attr'  => $options['dialog_attr'],
            'content_attr' => $options['content_attr'],
            'size'         => $options['size'],
        ));

        if ($block->getConfig()->hasAttribute('block_header')) {
            $view->vars = array_replace($view->vars, array(
                'block_header' => $block->getConfig()->getAttribute('block_header')->createView($view),
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'render_id'    => true,
            'transition'   => null,
            'dialog_attr'  => array(),
            'content_attr' => array(),
            'size'         => null,
        ));

        $resolver->setAllowedTypes(array(
            'id'           => 'string',
            'transition'   => array('null', 'string'),
            'dialog_attr'  => 'array',
            'content_attr' => 'array',
            'size'         => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'transition' => array('fade'),
            'style'      => array('lg', 'sm'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'modal';
    }
}
