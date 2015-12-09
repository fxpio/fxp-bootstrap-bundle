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
use Sonatra\Bundle\BlockBundle\Block\Util\BlockUtil;
use Sonatra\Bundle\BlockBundle\Block\Exception\InvalidConfigurationException;
use Sonatra\Bundle\BlockBundle\Block\Util\StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if (!empty($options['label'])) {
            $builder->add('header', PanelHeaderType::class, array());
            $builder->get('header')->add(null, HeadingType::class, array('size' => 4, 'label' => $options['label']));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (BlockUtil::isBlockType($child, PanelHeaderType::class)) {
            if ($block->getAttribute('has_header')) {
                $msg = 'The panel block "%s" has already panel header. Removes the label option of the panel block.';
                throw new InvalidConfigurationException(sprintf($msg, StringUtil::fqcnToBlockPrefix(get_class($block->getConfig()->getType()->getInnerType()), true)));
            }

            $block->setAttribute('has_header', true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (BlockUtil::isBlockType($child, PanelHeaderType::class)) {
            $block->setAttribute('has_header', false);
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

        if (!is_scalar($view->vars['value'])) {
            $view->vars['value'] = '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'style' => null,
        ));

        $resolver->setAllowedTypes('style', array('null', 'string'));

        $resolver->setAllowedValues('style', array(null, 'default', 'primary', 'success', 'info', 'warning', 'danger'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'panel';
    }
}
