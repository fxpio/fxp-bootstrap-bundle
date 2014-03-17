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
use Sonatra\Bundle\BlockBundle\Block\Exception\InvalidArgumentException;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class DataSource implements DataSourceInterface
{
    /**
     * @var BlockView
     */
    protected $tableView;

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
     * @var string
     */
    protected $rowId;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var int
     */
    protected $start;

    /**
     * @var int
     */
    protected $pageSize;

    /**
     * @var int
     */
    protected $pageNumber;

    /**
     * @var int
     */
    protected $pageCount;

    /**
     * @var array
     */
    protected $sortColumns;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $cacheRows;

    /**
     * Constructor.
     *
     * @param string $rowId The data fieldname for unique id row definition
     */
    public function __construct($rowId = null)
    {
        $this->columns = array();
        $this->locale = \Locale::getDefault();
        $this->rows = array();
        $this->rowId = $rowId;
        $this->start = 1;
        $this->pageSize = 0;
        $this->pageNumber = 1;
        $this->pageCount = 1;
        $this->sortColumns = array();
        $this->parameters = array();
    }

    /**
     * {@inheritdoc}
     */
    public function setTableView(BlockView $view)
    {
        $this->cacheRows = null;
        $this->tableView = $view;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTableView()
    {
        return $this->tableView;
    }

    /**
     * {@inheritdoc}
     */
    public function setColumns(array $columns)
    {
        $this->cacheRows = null;
        $this->columns = array_values($columns);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addColumn(BlockInterface $column, $index = null)
    {
        if (null !== $index) {
            array_splice($this->columns, $index, 0, $column);

        } else {
            array_push($this->columns, $column);
        }

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
        $this->cacheRows = null;
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
        $this->cacheRows = null;
        $this->rows = $rows;
        $this->setSize(count($rows));
        $this->setPageNumber(1);
        $this->setPageCount(0 === $this->getPageSize() ? 1 : (integer) ceil($this->getSize() / $this->getPageSize()));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRows()
    {
        if (null !== $this->cacheRows) {
            return $this->cacheRows;
        }

        $this->cacheRows = array();
        $startTo = ($this->getPageNumber() - 1) * $this->getPageSize();
        $endTo = $this->getPageSize();

        if (0 === $startTo && 0 === $endTo) {
            $endTo = $this->getSize();
        }

        $rowNumber = $this->getStart() + $startTo;
        $pagination = array_slice($this->rows, $startTo, $endTo);

        $this->cacheRows = $this->paginateRows($pagination, $rowNumber);

        return $this->cacheRows;
    }

    /**
     * {@inheritdoc}
     */
    public function setStart($start)
    {
        $this->cacheRows = null;
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
    public function setSize($size)
    {
        $this->cacheRows = null;
        $this->size = $size;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageSize($size)
    {
        $this->cacheRows = null;
        $this->pageSize = $size;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageNumber($number)
    {
        $this->cacheRows = null;
        $this->pageNumber = $number;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageCount($count)
    {
        $this->cacheRows = null;
        $this->pageCount = $count;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * {@inheritdoc}
     */
    public function setSortColumns(array $columns)
    {
        $this->cacheRows = null;
        $this->sortColumns = $columns;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSortColumns()
    {
        return $this->sortColumns;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters)
    {
        $this->cacheRows = null;
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Return the index name of column.
     *
     * @param string $name
     *
     * @return string The index
     *
     * @throws InvalidArgumentException When column does not exit
     */
    protected function getColumnIndex($name)
    {
        if (isset($this->columns[$name])) {
            return $this->columns[$name]->getConfig()->getOption('index');
        }

        throw new InvalidArgumentException(sprintf('The column name "%s" does not exist', $name));
    }

    /**
     * Get the field value of data row.
     *
     * @param array|object $dataRow
     * @param string       $name
     *
     * @return mixed|null
     */
    protected function getDataField($dataRow, $name)
    {
        if (is_array($dataRow)) {
            return isset($dataRow[$name]) ? $dataRow[$name] : null;
        }

        if (is_object($dataRow)) {
            $ref = new \ReflectionClass($dataRow);

            $method = 'get'.ucfirst($name);
            if ($ref->hasMethod($method)) {
                return $dataRow->$method();
            }

            $method = 'has'.ucfirst($name);
            if ($ref->hasMethod($method)) {
                return $dataRow->$method();
            }

            $method = 'is'.ucfirst($name);
            if ($ref->hasMethod($method)) {
                return $dataRow->$method();
            }
        }

        return null;
    }

    /**
     * Paginate the rows.
     *
     * @param array $pagination
     * @param int   $rowNumber
     *
     * @return array The paginated rows
     */
    protected function paginateRows(array $pagination, $rowNumber)
    {
        $cacheRows = array();

        // loop in rows
        foreach ($pagination as $key => $data) {
            $row = array(
                '_row_number'   => $rowNumber++,
                '_attr_columns' => array(),
            );

            if (null !== $this->rowId) {
                $rowId = $this->getDataField($data, $this->rowId);

                if (null !== $rowId) {
                    $row['_row_id'] = $rowId;
                }
            }

            // loop in cells
            foreach ($this->getColumns() as $rIndex => $column) {
                if (count($column->getConfig()->getOption('attr')) > 0) {
                    $row['_attr_columns'][$column->getName()] = $column->getConfig()->getOption('attr');
                }

                if ('_row_number' === $column->getConfig()->getOption('index')) {
                    continue;
                }

                $config = $column->getConfig();
                $formatter = $config->getOption('formatter');
                $cellData = $this->getDataField($data, $config->getOption('index'));
                $options = $config->getOption('formatter_options');

                if ('twig' === $formatter) {
                    $options = array_merge_recursive($options, array(
                        'variables' => array(
                            '_column'     => $column,
                            '_row_data'   => $data,
                            '_row_number' => $row['_row_number'],
                        )
                    ));
                }

                $cell = $config->getBlockFactory()->createNamed($column->getName(), $formatter, $cellData, $options);
                $value = $cell->getViewData();

                if ('' === $value) {
                    $value = $cell->getConfig()->getEmptyData();

                    if ($value instanceof \Closure) {
                        $value = call_user_func($value, $cell, $cell->getConfig()->getOptions());
                    }
                }

                // insert new value
                $row[$column->getName()] = $value;
            }

            if (0 === count($row['_attr_columns'])) {
                unset($row['_attr_columns']);
            }

            $cacheRows[] = $row;
        }

        return $cacheRows;
    }
}
