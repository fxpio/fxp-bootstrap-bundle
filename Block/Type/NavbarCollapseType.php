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

use Symfony\Component\OptionsResolver\OptionsResolver;

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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'chained_block' => true,
            'align' => null,
            'render_id' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'navbar_collapse';
    }
}
