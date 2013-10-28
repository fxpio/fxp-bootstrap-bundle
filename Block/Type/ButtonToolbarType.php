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
use Symfony\Component\OptionsResolver\Options;
use Sonatra\Bundle\BlockBundle\Block\Extension\Core\DataMapper\WrapperMapper;

/**
 * Button Toolbar Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ButtonToolbarType extends AbstractType
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
    public function getName()
    {
        return 'button_toolbar';
    }
}
