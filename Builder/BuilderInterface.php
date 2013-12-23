<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Builder;

/**
 * Builds the resource.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface BuilderInterface
{
    /**
     * Get the path of resource file.
     *
     * @return string The path
     */
    public function getPath();

    /**
     * Compile the stylesheet.
     */
    public function compile();

    /**
     * Get the path of resource file.
     *
     * @return string
     */
    public function __toString();
}
