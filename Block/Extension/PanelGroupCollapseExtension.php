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
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if ($options['collapse'] && BlockUtil::isValidBlock('panel', $child)) {
            foreach ($child->all() as $name => $subChild) {
                if (BlockUtil::isValidBlock('panel_header', $subChild)) {
                    foreach ($subChild->all() as $name => $subSubChild) {
                        if (BlockUtil::isValidBlock('heading', $subSubChild)) {
                            foreach ($subSubChild->all() as $name => $subSubSubChild) {
                                $subSubChild->remove($name);
                            }

                            $subSubChild->add('panel_link', 'link', array(
                                'label' => $subSubChild->getOption('label'),
                                'src' => '#' . BlockUtil::createBlockId($child) . 'Collapse',
                                'attr' => array('data-toggle' => 'collapse', 'data-parent' => '#' . BlockUtil::createBlockId($block))
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
