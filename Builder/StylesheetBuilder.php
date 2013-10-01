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
    protected $compilePath;

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
     * Preserves the order of loading of twitter bootstrap.
     * @var array
     */
    protected $orderComponents = array(
        'variables', 'default_variables', 'custom_variables',
        'mixins', 'custom_mixins',
        'normalize',
        'print',
        'scaffolding',
        'type',
        'code',
        'grid',
        'tables',
        'forms',
        'buttons',
        'component_animations',
        'glyphicons',
        'dropdowns',
        'button_groups',
        'input_groups',
        'navs',
        'navbar',
        'breadcrumbs',
        'pagination',
        'pager',
        'labels',
        'badges',
        'jumbotron',
        'thumbnails',
        'alerts',
        'progress_bars',
        'media',
        'list_group',
        'panels',
        'wells',
        'close',
        'modals',
        'tooltip',
        'popovers',
        'carousel',
        'utilities',
        'responsive_utilities',
    );

    /**
     * Constructor.
     *
     * @param string $cacheDir   The cache directory
     * @param string $directory  The bootstrap less directory
     * @param array  $components The bootstrap less components configuration
     */
    public function __construct($cacheDir, $directory, array $components)
    {
        $this->compilePath = sprintf('%s/bootstrap.less', $cacheDir);
        $this->directory = rtrim($directory, '/');
        $this->components = $components;
        $this->filesystem = new Filesystem();
    }

    /**
     * Get the path of the bootstrap.less file.
     *
     * @return string The path
     */
    public function getPath()
    {
        return $this->compilePath;
    }

    /**
     * Compile the stylesheet.
     */
    public function compile()
    {
        $content = '';

        foreach ($this->orderComponents as $component) {
            $content = $this->addImport($content, $component, $this->components[$component]);
        }

        $this->filesystem->dumpFile($this->compilePath, $content);
    }

    /**
     * Add import file in content.
     *
     * @param string      $content   The content
     * @param string      $component The name of component
     * @param string|bool $value     The value of component
     *
     * @return string The content
     */
    protected function addImport($content, $component, $value)
    {
        if (is_string($value)) {
            $content .= sprintf('@import "relative(%s)";', $value);
            $content .= PHP_EOL;

        } elseif ($value) {
            $content .= sprintf('@import "relative(%s/%s.less)";', $this->directory, str_replace('_', '-', $component));
            $content .= PHP_EOL;
        }

        return $content;
    }
}
