<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Block\DataSource;

use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface DataSourceInterface
{
    /**
     * Set table view.
     *
     * @param BlockView $view
     */
    public function setTableView(BlockView $view);

    /**
     * Get table view.
     *
     * @return BlockView
     */
    public function getTableView();

    /**
     * Set columns.
     *
     * @param array $columns The list of column BlockInterface
     *
     * @return DataSourceInterface
     */
    public function setColumns(array $columns);

    /**
     * Add column.
     *
     * @param BlockInterface $column
     * @param int            $index
     *
     * @return DataSourceInterface
     */
    public function addColumn(BlockInterface $column, $index = null);

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
     * @return array The list of column BlockInterface
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
     * Set size.
     *
     * @param int $size
     *
     * @return DataSourceInterface
     */
    public function setSize($size);

    /**
     * Get count of rows.
     *
     * @return int
     */
    public function getSize();

    /**
     * Set page size.
     * If page size equal 0, all row displayed.
     *
     * @param integer $size
     *
     * @return DataSourceInterface
     */
    public function setPageSize($size);

    /**
     * Get page size.
     * If page size equal 0, all row displayed.
     *
     * @return integer
    */
    public function getPageSize();

    /**
     * Set page number.
     *
     * @param integer $number
     *
     * @return DataSourceInterface
    */
    public function setPageNumber($number);

    /**
     * Get page number.
     *
     * @return integer
    */
    public function getPageNumber();

    /**
     * Set page count.
     *
     * @param integer
     *
     * @return DataSourceInterface
    */
    public function setPageCount($count);

    /**
     * Get page count.
     *
     * @return integer
    */
    public function getPageCount();

    /**
     * Set sort columns.
     *
     * @param array $columns
     *
     * @return DataSourceInterface
    */
    public function setSortColumns(array $columns);

    /**
     * Get sort columns.
     *
     * @return array
    */
    public function getSortColumns();

    /**
     * Set parameters.
     *
     * @param array $parameters
     *
     * @return DataSourceInterface
    */
    public function setParameters(array $parameters);

    /**
     * Get parameters.
     *
     * @return array
    */
    public function getParameters();
}
