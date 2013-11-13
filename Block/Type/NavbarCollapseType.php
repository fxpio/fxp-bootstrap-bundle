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

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Navbar Collapse Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class NavbarCollapseType extends AbstractNavbarItemType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'chained_block' => true,
            'align'         => null,
            'render_id'     => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'navbar_collapse';
    }
}
