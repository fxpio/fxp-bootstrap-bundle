<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Assetic\Factory\Resource;

use Assetic\Factory\Resource\ResourceInterface;

/**
 * Dynamic resource.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface DynamicResourceInterface extends ResourceInterface
{
    /**
     * Compile the resource.
     *
     * @param int $timestamp
     */
    public function compile($timestamp = null);
}
