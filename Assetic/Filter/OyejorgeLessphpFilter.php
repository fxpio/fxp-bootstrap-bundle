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
     * @var string
     */
    protected $rootDir;

    /**
     * @var array
     */
    protected $bundles;

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
    public function __construct($rootDir, array $bundles, array $options = array(), array $loadPaths = array())
    {
        $this->rootDir = $rootDir;
        $this->bundles = $bundles;
        $this->options = $options;
        $this->loadPaths = $loadPaths;
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
        $less->SetImportDirs($this->loadPaths);
        $less->parse($this->generateVariables());
        $less->parse($asset->getContent());

        $asset->setContent($less->getCss());
    }

    /**
     * {@inheritdoc}
     */
    public function filterDump(AssetInterface $asset)
    {
    }

    /**
     * Generate the less variables of kernel, vendor and bundles directory.
     *
     * All variables of bundle directory are named such as 'kernel.bundles' container parameters bag.
     *
     * Example:
     *  AcmeBlogBundle => '@AcmeBlogBundle'
     *
     * @return string
     */
    protected function generateVariables()
    {
        $output = sprintf('@symfony-kernel-root-dir: "%s";%s', $this->rootDir, PHP_EOL);
        $output .= sprintf('@symfony-vendor-dir: "%s";%s', $this->rootDir.'/../vendor', PHP_EOL);

        foreach ($this->bundles as $name => $class) {
            $ref = new \ReflectionClass($class);
            $dir = dirname($ref->getFileName());
            $dir = str_replace('\\', '/', $dir);

            $output .= sprintf('@%s: "%s";%s', $name, $dir, PHP_EOL);
        }

        return $output;
    }
}
