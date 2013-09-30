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

use Sonatra\Bundle\BootstrapBundle\Builder\StylesheetBuilder;

/**
 * A common stylesheets resource.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CommonStylesheetsResource extends SingleConfigurationResource
{
    /**
     * @var StylesheetBuilder
     */
    protected $builder;

    /**
     * Constructor.
     *
     * @param string            $name    The name of resource
     * @param array             $inputs  The input assets
     * @param array             $filters The filters for assets
     * @param array             $options The options for assets
     * @param StylesheetBuilder $builder The bootstrap stylesheet builder
     */
    public function __construct($name, array $inputs, array $filters, array $options, StylesheetBuilder $builder = null)
    {
        parent::__construct($name, $inputs, $filters, $options);

        $this->builder = $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        if (null === $this->builder) {
            return true;
        }

        return file_exists($this->builder->getPath()) && filemtime($this->builder->getPath()) <= $timestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        if (null !== $this->builder) {
            if (!in_array($this->builder->getPath(), $this->inputs)) {
                array_unshift($this->inputs, $this->builder->getPath());
            }

            if (!file_exists($this->builder->getPath())) {
                $this->builder->compile();
            }
        }

        return parent::getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'sonatra_bootstrap_common_stylesheet';
    }
}
