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

use Symfony\Bundle\AsseticBundle\Factory\Resource\ConfigurationResource;

/**
 * A single configured resource.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SingleConfigurationResource extends ConfigurationResource
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $inputs;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param string $name    The name of resource
     * @param array  $inputs  The input assets
     * @param array  $filters The filters for assets
     * @param array  $options The options for assets
     */
    public function __construct($name, array $inputs, array $filters, array $options)
    {
        $this->name = $name;
        $this->inputs = $inputs;
        $this->filters = $filters;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return array($this->name => array($this->inputs, $this->filters, $this->options));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'sonatra_bootstrap';
    }
}
