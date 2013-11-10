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
 * Panel Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelType extends AbstractType
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
            $blockHeader = $this->factory->createNamed('header', 'panel_header', null, array());
            $blockHeader->add(null, 'heading', array('size' => 4, 'label' => $options['label']));

            $builder->setAttribute('block_header', $blockHeader);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'style' => $options['style'],
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
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $name => $child) {
            if (in_array('panel_header', $child->vars['block_prefixes'])) {
                $view->vars['block_header'] = $child;
                unset($view->children[$name]);

            } elseif (in_array('panel_footer', $child->vars['block_prefixes'])) {
                $view->vars['block_footer'] = $child;
                unset($view->children[$name]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'style' => null,
        ));

        $resolver->setAllowedTypes(array(
            'style' => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'style' => array('default', 'primary', 'success', 'info', 'warning', 'danger'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'panel';
    }
}
