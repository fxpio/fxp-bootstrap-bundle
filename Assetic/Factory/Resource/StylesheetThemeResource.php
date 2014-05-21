<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Assetic\Factory\Resource;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Stylesheet theme resource.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class StylesheetThemeResource implements DynamicResourceInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor.
     *
     * @param string $cacheDir  The cache directory
     * @param string $directory The bootstrap less directory
     */
    public function __construct($cacheDir, $directory)
    {
        $this->path = sprintf('%s/theme.less', $cacheDir);
        $this->directory = rtrim($directory, '/');
        $this->filesystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        $fresh = file_exists($this->path) && filemtime($this->path) <= $timestamp;

        if ($fresh) {
            $pathVariables = sprintf('%s/variables.less', $this->directory);
            $pathMixins = sprintf('%s/mixins.less', $this->directory);

            if (!(file_exists($pathVariables) && filemtime($pathVariables) <= $timestamp)) {
                return false;
            }

            if (!(file_exists($pathMixins) && filemtime($pathMixins) <= $timestamp)) {
                return false;
            }
        }

        return $fresh;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return file_get_contents($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public function compile($timestamp = null)
    {
        $content = file_get_contents(sprintf('%s/theme.less', $this->directory));

        $content = str_replace('@import "variables.less";', sprintf('@import "%s/variables.less";', $this->directory), $content);
        $content = str_replace('@import "mixins.less";', sprintf('@import "%s/mixins.less";', $this->directory), $content);

        $this->filesystem->dumpFile($this->path, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->path;
    }
}
