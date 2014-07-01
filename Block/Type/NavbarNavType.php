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
 * Navbar Nav Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class NavbarNavType extends AbstractNavbarItemType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'nav';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'navbar_nav';
    }
}
