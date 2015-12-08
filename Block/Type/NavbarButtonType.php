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

/**
 * Navbar Button Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class NavbarButtonType extends AbstractNavbarItemType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ButtonType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'navbar_button';
    }
}
