<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Assetic\Filter;

use Assetic\Filter\FilterInterface;
use Assetic\Asset\AssetInterface;

/**
 * Loads LESS files using the PHP implementation of less, oyejorge lessphp.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class OyejorgeLessphpFilter implements FilterInterface
{
    /**
     * Lessphp options.
     *
     * @var array
     */
    protected $options;

    /**
     * Lessphp load paths.
     *
     * @var array
     */
    protected $loadPaths;

    /**
     * Constructor.
     */
    public function __construct(array $options = array(), array $loadPaths = array())
    {
        $this->options = $options;
        $this->loadPaths = array();

        foreach ($loadPaths as $path) {
            $this->loadPaths[$path] = '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function filterLoad(AssetInterface $asset)
    {
        if (!preg_match('/\.less$/', $asset->getSourcePath())) {
            return;
        }

        $less = new \Less_Parser($this->options);
        $dir = $this->loadPaths;

        if ($asset->getSourceDirectory()) {
            $dir = array_merge($dir, array($asset->getSourceDirectory() => ''));
        }

        $less->SetImportDirs($dir);
        $less->parse($asset->getContent());

        $asset->setContent($less->getCss());
    }

    /**
     * {@inheritdoc}
     */
    public function filterDump(AssetInterface $asset)
    {
    }
}
