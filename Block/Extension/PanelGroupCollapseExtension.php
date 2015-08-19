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
use Sonatra\Bundle\BlockBundle\Block\Util\BlockUtil;
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
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if ($options['collapsible'] && BlockUtil::isValidBlock('panel', $child)) {
            /* @var BlockInterface $subChild */
            foreach ($child->all() as $subChild) {
                if (BlockUtil::isValidBlock('panel_header', $subChild)) {
                    /* @var BlockInterface $subSubChild */
                    foreach ($subChild->all() as $subSubChild) {
                        if (BlockUtil::isValidBlock('heading', $subSubChild)) {
                            foreach ($subSubChild->all() as $name => $subSubSubChild) {
                                $subSubChild->remove($name);
                            }

                            $subSubChild->add('panel_link', 'link', array(
                                'label' => $subSubChild->getOption('label'),
                                'src' => '#'.BlockUtil::createBlockId($child).'Collapse',
                                'attr' => array('data-toggle' => 'collapse', 'data-parent' => '#'.BlockUtil::createBlockId($block)),
                            ));

                            $subSubChild->setOption('label', null);
                        }
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'collapsible' => $options['collapsible'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        $first = null;

        if ($options['collapsible']) {
            foreach ($view->children as $name => $child) {
                $child->vars['group_collapse'] = true;

                if (null === $first) {
                    $first = $name;

                    if ($options['collapse_first']) {
                        $child->vars['group_collapse_in'] = true;
                    }
                }

                if (in_array($view->vars['id'], $options['collapse_ins'])) {
                    $child->vars['group_collapse_in'] = true;
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
            'collapsible'    => false,
            'collapse_first' => false,
            'collapse_ins'   => array(),
            'render_id'      => function (Options $options) {
                return $options['collapsible'];
            },
        ));

        $resolver->addAllowedTypes(array(
            'collapsible'    => 'bool',
            'collapse_first' => 'bool',
            'collapse_ins'   => 'array',
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
