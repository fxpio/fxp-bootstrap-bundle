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

/**
 * Stylesheet resource.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class StylesheetResource extends AbstractDynamicResource
{
    /**
     * Preserves the order of loading of twitter bootstrap.
     * @var array
     */
    protected $orderComponents = array(
        'variables', 'custom_variables',
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
     * @param array  $bundles    The bundles directories
     */
    public function __construct($cacheDir, $directory, array $components, array $bundles)
    {
        parent::__construct(sprintf('%s/bootstrap.less', $cacheDir), $directory, $components, $bundles);
    }
}
