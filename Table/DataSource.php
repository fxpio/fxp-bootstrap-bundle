<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Table;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class DataSource implements DataSourceInterface
{
    /**
     * @var array
     */
    protected $columns;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var array
     */
    protected $rows;

    /**
     * @var int
     */
    protected $start;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->columns = array();
        $this->locale = \Locale::getDefault();
        $this->rows = array();
        $this->start = 1;
    }

    /**
     * {@inheritdoc}
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * {@inheritdoc}
     */
    public function addColumn($name, $index = null)
    {
        if (null == $index) {
            array_splice($this->columns, $index, 0, $name);

            return $this;
        }

        array_push($this->columns, $name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeColumn($index)
    {
        array_splice($this->columns, $index, 1);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale = null)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * {@inheritdoc}
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return count($this->rows);
    }
}
