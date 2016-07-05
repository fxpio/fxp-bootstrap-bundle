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

/**
 * Dropdown Item Prepend Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class DropdownItemPrependType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dropdown_item_prepend';
    }
}
