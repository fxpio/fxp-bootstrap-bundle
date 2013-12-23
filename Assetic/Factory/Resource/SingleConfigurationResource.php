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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sonatra\Bundle\BootstrapBundle\Builder\BuilderInterface;

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
     * @param string             $name      The name of resource
     * @param array              $inputs    The input assets
     * @param array              $filters   The filters for assets
     * @param array              $options   The options for assets
     * @param ContainerInterface $container The container service
     * @param BuilderInterface[] $builders  The builders
     */
    public function __construct($name, array $inputs, array $filters, array $options, ContainerInterface $container = null, array $builders = array())
    {
        $this->name = $name;
        $this->filters = $filters;
        $this->options = $options;

        $newInputs = array();

        if (null !== $container) {
            foreach ($builders as $builder) {
                $newInputs[] = $container->get($builder);
            }
        }

        $this->inputs = array_merge($newInputs, $inputs);
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        foreach ($this->inputs as $input) {
            if (!(file_exists($input) && filemtime($input) <= $timestamp)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        $inputs = array();

        foreach ($this->inputs as $input) {
            if ($input instanceof BuilderInterface && !file_exists($input->getPath())) {
                $input->compile();
            }

            $inputs[] = (string) $input;
        }

        return array($this->name => array($inputs, $this->filters, $this->options));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'sonatra_bootstrap';
    }
}
