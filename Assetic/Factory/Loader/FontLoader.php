<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Assetic\Factory\Loader;

use Assetic\Factory\Loader\FormulaLoaderInterface;
use Assetic\Factory\Resource\ResourceInterface;
use Assetic\Factory\Resource\IteratorResourceInterface;

/**
 * Creates formulaes for font resources.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FontLoader implements FormulaLoaderInterface
{
    /**
     * @var string
     */
    protected $output;

    /**
     * @var array
     */
    protected $webfontExtensions = array('eot', 'ttf', 'otf', 'woff', 'svg');

    /**
     * Constructor.
     *
     * @param string $output
     */
    public function __construct($output)
    {
        $this->output = rtrim($output, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function load(ResourceInterface $resource)
    {
        $formulae = array();

        if (!$resource instanceof IteratorResourceInterface) {
            return $formulae;
        }

        foreach ($resource as $font) {
            $file = new \SplFileInfo($font);

            if (!in_array($file->getExtension(), $this->webfontExtensions)) {
                continue;
            }

            $name = 'font_' . str_replace(array('-', '.', ' '), '_', $file->getFilename());

            $formulae[$name] = array(
                // inputs
                array($file->getPathname()),
                // filters
                array(),
                // options
                array(
                    'output' => sprintf($this->output.'/%s', $file->getBasename())
                )
            );
        }

        return $formulae;
    }
}
