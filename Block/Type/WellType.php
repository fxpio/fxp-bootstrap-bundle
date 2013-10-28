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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonatra\Bundle\BlockBundle\Block\Extension\Core\DataMapper\WrapperMapper;

/**
 * Well Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class WellType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper(new WrapperMapper());
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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'size' => null,
        ));

        $resolver->setAllowedTypes(array(
            'size' => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'size' => array('sm', 'lg'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'well';
    }
}
