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
use Sonatra\Bundle\BlockBundle\Block\BlockFactoryInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockBuilderInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Panel Group Collapse Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelGroupCollapseExtension extends AbstractTypeExtension
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
        if (!empty($options['collapse'])) {
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
            'collapse' => $options['collapse'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        $first = null;
        $hasChildActive = false;

        if ($options['collapse']) {
            foreach ($view->children as $name => $child) {
                $child->vars['group_collapse'] = true;

                if (null === $first) {
                    $first = $name;
                }

                if (isset($child->vars['collapse_in']) && $child->vars['collapse_in']) {
                    $hasChildActive = true;
                }

                $blockHeader = $this->factory->createNamed('panel_header', 'panel_header', null, array());
                $blockHeader->add('panel_heading', 'heading', array('size' => 4));
                $blockHeader->get('panel_heading')->add('panel_link', 'link', array(
                    'label' => $child->vars['label'],
                    'src' => '#'.$child->vars['id'].'Collapse',
                    'attr' => array('data-toggle' => 'collapse', 'data-parent' => '#'.$view->vars['id'])
                ));

                $child->vars['block_header'] = $blockHeader->createView($child);
            }

            if (null !== $first && !$hasChildActive && $options['collapse_first']) {
                $view->children[$first]->vars['collapse_in'] = true;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'collapse'       => false,
            'collapse_first' => false,
            'render_id'      => function (Options $options) {
                return $options['collapse'];
            },
        ));

        $resolver->addAllowedTypes(array(
            'collapse'       => 'bool',
            'collapse_first' => 'bool',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'panel_group';
    }
}
