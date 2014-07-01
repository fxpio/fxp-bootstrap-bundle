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
 * Clearfix Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ClearfixType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'clearfix';
    }
}
