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
use Sonatra\Bundle\BootstrapBundle\Assetic\Factory\Resource\DynamicResourceInterface;

/**
 * Creates formulaes for configuration resources.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigurationLoader implements FormulaLoaderInterface
{
    /**
     * @var boolean
     */
    private $debug;

    /**
     * @var int
     */
    private $lastTime;

    /**
     * Constructor.
     *
     * @param boolean $debug The debug mode
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
        $this->lastTime = (new \DateTime())->getTimestamp();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ResourceInterface $resource)
    {
        if ($resource instanceof DynamicResourceInterface) {
            if ($this->debug && !$resource->isFresh($this->lastTime)) {
                $resource->compile($this->lastTime);
                $this->lastTime = (new \DateTime())->getTimestamp();
            }

            return $resource->getContent();
        }

        return array();
    }
}
