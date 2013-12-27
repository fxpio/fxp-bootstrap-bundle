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

use Sonatra\Bundle\BootstrapBundle\Assetic\Util\ContainerUtils;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Stylesheet resource.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class StylesheetResource implements DynamicResourceInterface
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
        'blocks',
        'addon'
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
        $this->path = sprintf('%s/bootstrap.less', $cacheDir);
        $this->directory = rtrim($directory, '/');
        $this->components = $components;
        $this->filesystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        return file_exists($this->path) && filemtime($this->path) <= $timestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        $this->compile();

        return file_get_contents($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        if (!file_exists($this->path)) {
            $content = '';

            foreach ($this->orderComponents as $component) {
                $content = $this->addImport($content, $component, $this->components[$component]);
            }

            $this->filesystem->dumpFile($this->path, $content);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->path;
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
            $value = ContainerUtils::filterBundles($value, function ($matches) {
                return '@{' . $matches[1] . 'Bundle}';
            });

            $content .= sprintf('@import "%s";', $value);
            $content .= PHP_EOL;

        } elseif ($value) {
            $content .= sprintf('@import "%s/%s.less";', $this->directory, str_replace('_', '-', $component));
            $content .= PHP_EOL;
        }

        return $content;
    }
}
