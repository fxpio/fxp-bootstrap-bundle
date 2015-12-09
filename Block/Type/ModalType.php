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
 * Modal Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ModalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if (!empty($options['label'])) {
            $builder->add('header', ModalHeaderType::class, array('label' => $options['label']));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (BlockUtil::isValidBlock(ModalHeaderType::class, $child)) {
            if ($block->getAttribute('has_header')) {
                $msg = 'The modal block "%s" has already modal header. Removes the label option of the modal block.';
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
        if (BlockUtil::isValidBlock(ModalHeaderType::class, $child)) {
            $block->setAttribute('has_header', false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'transition' => $options['transition'],
            'dialog_attr' => $options['dialog_attr'],
            'content_attr' => $options['content_attr'],
            'size' => $options['size'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $name => $child) {
            if (in_array('modal_header', $child->vars['block_prefixes'])) {
                $view->vars['block_header'] = $child;
                unset($view->children[$name]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'render_id' => true,
            'transition' => null,
            'dialog_attr' => array(),
            'content_attr' => array(),
            'size' => null,
        ));

        $resolver->setAllowedTypes('id', 'string');
        $resolver->setAllowedTypes('transition', array('null', 'string'));
        $resolver->setAllowedTypes('dialog_attr', 'array');
        $resolver->setAllowedTypes('content_attr', 'array');
        $resolver->setAllowedTypes('size', array('null', 'string'));

        $resolver->setAllowedValues('transition', array(null, 'fade'));
        $resolver->setAllowedValues('size', array(null, 'lg', 'sm'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'modal';
    }
}
