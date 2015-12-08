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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * List Group Item Heading Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ListGroupItemHeadingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'size' => 4,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return HeadingType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'list_group_item_heading';
    }
}
