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
interface DataSourceInterface
{
    /**
     * Set columns.
     *
     * @param array $columns The column names
     *
     * @return DataSourceInterface
     */
    public function setColumns(array $columns);

    /**
     * Add column.
     *
     * @param string $name
     * @param int    $index
     *
     * @return DataSourceInterface
     */
    public function addColumn($name, $index = null);

    /**
     * Remove column.
     *
     * @param int $index
     *
     * @return DataSourceInterface
     */
    public function removeColumn($index);

    /**
     * Get columns.
     *
     * @return array
     */
    public function getColumns();

    /**
     * Set locale.
     *
     * @param string $locale
     *
     * @return DataSourceInterface
     */
    public function setLocale($locale);

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale();

    /**
     * Set rows.
     *
     * @param \Iterator|array $rows
     *
     * @return DataSourceInterface
     */
    public function setRows($rows);

    /**
     * Get rows.
     *
     * @return \Iterator
     */
    public function getRows();

    /**
     * Set start.
     *
     * @param int $start
     *
     * @return DataSourceInterface
     */
    public function setStart($start);

    /**
     * Get start.
     *
     * @return int
     */
    public function getStart();

    /**
     * Get count of rows.
     *
     * @return int
     */
    public function getSize();
}
