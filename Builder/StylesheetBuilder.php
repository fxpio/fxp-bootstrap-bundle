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

use Sonatra\Bundle\BootstrapBundle\Exception\CompileException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Builds the bootstrap stylesheet.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class StylesheetBuilder
{
    /**
     * @var string
     */
    protected $cachePath;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var array
     */
    protected $components;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor.
     */
    public function __construct($cachePath, $directory, array $components)
    {
        $this->cachePath = $cachePath;
        $this->directory = rtrim($directory, '/');
        $this->components = $components;
        $this->filesystem = new Filesystem();
    }

    /**
     * Get the path of the bootstrap.less file.
     *
     * @return string The path
     *
     * @throws LogicException When the less file is not compiled
     */
    public function getPath()
    {
        if (!file_exists($this->cachePath)) {
            throw new CompileException(sprintf('The stylesheet of bootstrap file must be compiled before, at the "%s" path', $this->cachePath));
        }

        return $this->cachePath;
    }

    /**
     * Compile the stylesheet.
     */
    public function compile()
    {
        $data = '';

        foreach ($this->components as $component => $value) {
            if (is_string($value)) {
                $data .= sprintf('@import "relative(%s)";', $value);
                $data .= PHP_EOL;

            } elseif ($value) {
                $data .= sprintf('@import "relative(%s/%s.less)";', $this->directory, $component);
                $data .= PHP_EOL;
            }
        }

        $this->filesystem->dumpFile($this->cachePath, $data);
    }
}
