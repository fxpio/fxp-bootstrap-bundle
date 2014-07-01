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

use Assetic\Exception\FilterException;
use Assetic\Asset\AssetInterface;
use Assetic\Util\CssUtils;

/**
 * Path Utils.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class PathUtils
{
    const REGEX_RELATIVES = '/relative\((["\']?)(?P<relative>.*?)(\\1)\)/';

    /**
     * Filters all CSS url()'s through a callable.
     *
     * @param string   $content  The CSS
     * @param callable $callback A PHP callable
     *
     * @return string The filtered CSS
     */
    public static function filterRelative($content, $callback)
    {
        $pattern = static::REGEX_RELATIVES;

        return CssUtils::filterCommentless($content, function ($part) use (& $callback, $pattern) {
            return preg_replace_callback($pattern, $callback, $part);
        });
    }

    /**
     * Convert the target (file or directory) to the relative path since a
     * asset directory.
     *
     * @param string|AssetInterface $path   The path or asset file
     * @param string                $target The file or directory to be convert
     *
     * @return string The relative target since the asset directory
     *
     * @throws FilterException When the target does not exist
     */
    public static function convertToRelative($path, $target)
    {
        if ($path instanceof AssetInterface) {
            $path = $path->getSourceRoot() . '/' . $path->getSourcePath();
        }

        $path = str_replace('\\', '/', $path);

        if (!realpath($target)) {
            throw new FilterException(sprintf('The target "%s" does not exist in "%s" file', $target, $path));
        }

        $value = '';
        $sameDir = '';
        $target = str_replace('\\', '/', realpath($target));
        $sourceDir = str_replace('\\', '/', realpath($path));

        if (is_file($path)) {
            $sourceDir = str_replace('\\', '/', realpath(dirname($path)));
        }

        $targetList = explode('/', $target);
        $sourceList = explode('/', $sourceDir);

        $backNumber = count($sourceList);
        $max = min(array(count($targetList), $backNumber));

        for ($i = 0; $i<$max; $i++) {
            if (isset($targetList[$i]) && isset($sourceList[$i]) && $targetList[$i] === $sourceList[$i]) {
                --$backNumber;

                $sameDir .= $targetList[$i];

                if (is_dir($sameDir)) {
                    $sameDir .= '/';
                }
            }
        }

        for ($i = 0; $i<$backNumber; $i++) {
            $value .= '../';
        }

        for ($i = (count($sourceList) - $backNumber); $i<count($targetList); $i++) {
            $value .= $targetList[$i];

            if (is_dir($sourceDir.'/'.$value)) {
                $value .= '/';
            }
        }

        return rtrim($value, '/');
    }

    final private function __construct() { }
}
