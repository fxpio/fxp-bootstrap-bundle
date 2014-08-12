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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sonatra\Bundle\BootstrapBundle\Assetic\Util\ContainerUtils;
use Assetic\Factory\Resource\ResourceInterface;

/**
 * A single configured resource.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SingleConfigurationResource implements DynamicResourceInterface
{
    /**
     * @var ContainerInterface
     */
    public $container;

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
     * @var array|ResourceInterface[]|DynamicResourceInterface[]
     */
    protected $resources;

    /**
     * Constructor.
     *
     * @param string                                         $name      The name of resource
     * @param array                                          $inputs    The input assets
     * @param array                                          $filters   The filters for assets
     * @param array                                          $options   The options for assets
     * @param ResourceInterface[]|DynamicResourceInterface[] $resources The resources
     */
    public function __construct($name, array $inputs, array $filters, array $options, array $resources = array())
    {
        $this->name = $name;
        $this->inputs = $inputs;
        $this->filters = $filters;
        $this->options = $options;
        $this->resources = $resources;
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        foreach ($this->getInputs() as $input) {
            if ($input instanceof ResourceInterface) {
                if (!$input->isFresh($timestamp)) {
                    return false;
                }

            } elseif (!(file_exists($input) && filemtime($input) <= $timestamp)) {
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

        foreach ($this->getInputs() as $input) {
            if ($input instanceof DynamicResourceInterface && !file_exists((string) $input)) {
                $input->compile();
            }

            $inputs[] = (string) $input;
        }

        return array($this->name => array($inputs, $this->filters, $this->options));
    }

    /**
     * {@inheritdoc}
     */
    public function compile($timestamp = null)
    {
        foreach ($this->getInputs() as $input) {
            if ($input instanceof DynamicResourceInterface && !$input->isFresh($timestamp)) {
                $input->compile($timestamp);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'sonatra_bootstrap';
    }

    /**
     * Gets the inputs with the merging the inputs defined in config with
     * resources add in the compiler pass (the order is conserved)
     *
     * @return array
     */
    protected function getInputs()
    {
        if (null === $this->container) {
            return $this->inputs;
        }

        $bundles = $this->container->getParameter('kernel.bundles');
        $resovedInputs = array();
        $unresovedInputs = array();

        // get resource filename defined in bundles
        foreach ($this->inputs as $input) {
            $resovedInput = ContainerUtils::filterBundles($input, function ($matches) use ($bundles) {
                $name = sprintf('%sBundle', $matches[1]);

                if (array_key_exists($name, $bundles)) {
                    $ref = new \ReflectionClass($bundles[$name]);
                    $dir = dirname($ref->getFileName());

                    return str_replace('\\', '/', $dir);
                }

                return $matches[0];
            });

            $resovedInputs[] = $resovedInput;
        }

        // replace filenames by resources
        foreach ($this->resources as $resource) {
            $res = $this->container->get($resource);
            $pos = array_search((string) $res, $resovedInputs);

            if (false !== $pos) {
                $this->inputs[$pos] = $res;

                continue;
            }

            $unresovedInputs[] = $res;
        }

        // moves the unresolved resources to the top array
        $this->inputs = array_merge($unresovedInputs, $this->inputs);
        $this->resources = array();
        $this->container = null;

        return $this->inputs;
    }
}
