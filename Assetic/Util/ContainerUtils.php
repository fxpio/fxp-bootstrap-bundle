<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Assetic\Util;

use Assetic\Util\CssUtils;

/**
 * Container Service Utils.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class ContainerUtils
{
    const REGEX_PARAMETER_BAG = '/%([a-z._-]+)%/';
    const REGEX_BUNDLE        = '/@([A-Za-z]+)Bundle/';

    /**
     * Filters all CSS url()'s through a callable.
     *
     * @param string   $content  The CSS
     * @param callable $callback A PHP callable
     *
     * @return string The filtered CSS
     */
    public static function filterParameters($content, $callback)
    {
        $pattern = static::REGEX_PARAMETER_BAG;

        return CssUtils::filterCommentless($content, function ($part) use (& $callback, $pattern) {
            return preg_replace_callback($pattern, $callback, $part);
        });
    }

    /**
     * Filters all CSS bundle's through a callable.
     *
     * @param string   $content  The CSS
     * @param callable $callback A PHP callable
     *
     * @return string The filtered CSS
     */
    public static function filterBundles($content, $callback)
    {
        $pattern = static::REGEX_BUNDLE;

        return CssUtils::filterCommentless($content, function ($part) use (& $callback, $pattern) {
            return preg_replace_callback($pattern, $callback, $part);
        });
    }

    final private function __construct() { }
}
