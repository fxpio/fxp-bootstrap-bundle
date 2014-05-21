<?php

/**
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
     * @param string                                          $name      The name of resource
     * @param array                                           $inputs    The input assets
     * @param array                                           $filters   The filters for assets
     * @param array                                           $options   The options for assets
     * @param ContainerInterface                              $container The container service
     * @param ResourceInterface[]| DynamicResourceInterface[] $resources The resources
     */
    public function __construct($name, array $inputs, array $filters, array $options, ContainerInterface $container = null, array $resources = array())
    {
        $this->name = $name;
        $this->inputs = $this->mergeInputs($inputs, $container, $resources);
        $this->filters = $filters;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        foreach ($this->inputs as $input) {
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

        foreach ($this->inputs as $input) {
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
        foreach ($this->inputs as $input) {
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
     * Merge the inputs defined in config with resources added in compiler pass
     * (the order is conserved).
     *
     * @param array              $inputs    The input assets
     * @param ContainerInterface $container The container service
     * @param array              $resources The resources
     *
     * @return array
     */
    protected function mergeInputs(array $inputs, ContainerInterface $container = null, array $resources = array())
    {
        if (null === $container) {
            return $inputs;
        }

        $bundles = $container->getParameter('kernel.bundles');
        $resovedInputs = array();
        $unresovedInputs = array();

        // get resource filename defined in bundles
        foreach ($inputs as $input) {
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
        foreach ($resources as $resource) {
            $res = $container->get($resource);
            $pos = array_search((string) $res, $resovedInputs);

            if (false !== $pos) {
                $inputs[$pos] = $res;

                continue;
            }

            $unresovedInputs[] = $res;
        }

        // moves the unresolved resources to the top array
        $inputs = array_merge($unresovedInputs, $inputs);

        return $inputs;
    }
}
