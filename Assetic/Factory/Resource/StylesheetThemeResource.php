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
     * @var bool|string
     */
    protected $theme;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor.
     *
     * @param string      $cacheDir  The cache directory
     * @param string      $directory The bootstrap less directory
     * @param bool|string $theme     The path of custom theme or the activation or not of default theme compilation
     */
    public function __construct($cacheDir, $directory, $theme)
    {
        $this->path = sprintf('%s/theme.less', $cacheDir);
        $this->directory = rtrim($directory, '/');
        $this->theme = $theme;
        $this->filesystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        if (false === $this->theme) {
            return true;
        }

        return file_exists($this->getPath()) && filemtime($this->getPath()) <= $timestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        if (false === $this->theme) {
            return '';
        }

        $this->compile();

        return file_get_contents($this->getPath());
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        if (true === $this->theme && !file_exists($this->path)) {
            $content = file_get_contents(sprintf('%s/theme.less', $this->directory));

            $content = str_replace('@import "variables.less";', sprintf('@import "relative(%s/variables.less)";', $this->directory), $content);
            $content = str_replace('@import "mixins.less";', sprintf('@import "relative(%s/mixins.less)";', $this->directory), $content);

            $this->filesystem->dumpFile($this->path, $content);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getPath();
    }

    /**
     * {@inheritdoc}
     */
    protected function getPath()
    {
        if (is_string($this->theme)) {
            return $this->theme;
        }

        return $this->path;
    }
}
