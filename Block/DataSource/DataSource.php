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

use Sonatra\Bundle\BlockBundle\Block\BlockBuilderInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockRendererInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Sonatra\Bundle\BlockBundle\Block\Exception\InvalidArgumentException;
use Sonatra\Bundle\BlockBundle\Block\Exception\InvalidConfigurationException;
use Sonatra\Bundle\BlockBundle\Block\Extension\Core\Type\TwigType;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class DataSource implements DataSourceInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;

    /**
     * @var BlockRendererInterface
     */
    protected $renderer;

    /**
     * @var BlockView
     */
    protected $tableView;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var array
     */
    protected $mappingColumns;

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
    protected $pageSize;

    /**
     * @var int
     */
    protected $pageSizeMax;

    /**
     * @var int
     */
    protected $pageNumber;

    /**
     * @var array
     */
    protected $sortColumns;

    /**
     * @var array
     */
    protected $mappingSortColumns;

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
     * @param string                    $rowId            The data fieldname for unique id row definition
     * @param PropertyAccessorInterface $propertyAccessor The property accessor
     */
    public function __construct($rowId = null, PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
        $this->columns = array();
        $this->mappingColumns = null;
        $this->locale = \Locale::getDefault();
        $this->rows = array();
        $this->rowId = $rowId;
        $this->pageSize = 0;
        $this->pageSizeMax = 0;
        $this->pageNumber = 1;
        $this->sortColumns = array();
        $this->mappingSortColumns = array();
        $this->parameters = array();
    }

    /**
     * {@inheritdoc}
     */
    public function setRenderer(BlockRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function setTableView(BlockView $view)
    {
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
        $this->mappingColumns = null;
        $this->columns = array_values($columns);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addColumn(BlockInterface $column, $index = null)
    {
        $this->cacheRows = null;
        $this->mappingColumns = null;

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
        $this->cacheRows = null;
        $this->mappingColumns = null;

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
        $this->size = null;
        $this->rows = $rows;
        $this->pageNumber = 1;

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

        $pagination = array_slice($this->rows, $startTo, $endTo);
        $this->cacheRows = $this->paginateRows($pagination, $this->getStart());

        return $this->cacheRows;
    }

    /**
     * {@inheritdoc}
     */
    public function getStart()
    {
        return ($this->getPageNumber() - 1) * $this->getPageSize() + 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnd()
    {
        return 0 === $this->getPageSize()
            ? $this->getSize()
            : min($this->getSize(), ($this->getPageSize() * $this->getPageNumber()));
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        if (null === $this->size) {
            $this->size = $this->calculateSize();
        }

        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageSize($size)
    {
        $this->cacheRows = null;
        $this->pageSize = 0 === $this->getPageSizeMax() ? $size : min($size, $this->getPageSizeMax());

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
    public function setPageSizeMax($size)
    {
        $this->cacheRows = null;
        $this->pageSizeMax = $size;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageSizeMax()
    {
        return $this->pageSizeMax;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageNumber($number)
    {
        $this->cacheRows = null;
        $this->pageNumber = min($number, $this->getPageCount());
        $this->pageNumber = max($this->pageNumber, 1);

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
    public function getPageCount()
    {
        return 0 === $this->getPageSize() ? 1 : (integer) ceil($this->getSize() / $this->getPageSize());
    }

    /**
     * {@inheritdoc}
     */
    public function setSortColumns(array $columns)
    {
        $this->cacheRows = null;
        $this->sortColumns = array();
        $this->mappingSortColumns = array();

        foreach ($columns as $i => $column) {
            if (!isset($column['name'])) {
                throw new InvalidArgumentException('The "name" property of sort column must be present ("sort" property is optional)');
            }

            if (isset($column['sort']) && 'asc' !== $column['sort'] && 'desc' !== $column['sort']) {
                throw new InvalidArgumentException('The "sort" property of sort column must have "asc" or "desc" value');
            }

            if ($this->isSorted($column['name'])) {
                throw new InvalidArgumentException(sprintf('The "%s" column is already sorted', $column['name']));
            }

            $this->sortColumns[] = $column;
            $this->mappingSortColumns[$column['name']] = $i;
        }

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
    public function getSortColumn($column)
    {
        $val = null;

        if ($this->isSorted($column)) {
            $def = $this->sortColumns[$this->mappingSortColumns[$column]];

            if (isset($def['sort'])) {
                $val = $def['sort'];
            }
        }

        return $val;
    }

    /**
     * {@inheritdoc}
     */
    public function isSorted($column)
    {
        return array_key_exists($column, $this->mappingSortColumns);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters)
    {
        $this->cacheRows = null;
        $this->size = null;
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
     * Calculates the total size of the source.
     *
     * @return int
     */
    protected function calculateSize()
    {
        return count($this->rows);
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
        if (!is_array($this->mappingColumns)) {
            $this->mappingColumns = array();

            /* @var BlockInterface $column */
            foreach ($this->getColumns() as $i => $column) {
                $this->mappingColumns[$column->getName()] = $i;
            }
        }

        if (isset($this->mappingColumns[$name])) {
            $column = $this->columns[$this->mappingColumns[$name]];

            return $column->getOption('index');
        }

        throw new InvalidArgumentException(sprintf('The column name "%s" does not exist', $name));
    }

    /**
     * Format the index without prefix with dot.
     *
     * @param string $name The index
     *
     * @return string
     */
    protected function formatIndex($name)
    {
        if (is_string($name)) {
            $exp = explode('.', $name);

            if (0 < count($exp)) {
                $name = $exp[count($exp) - 1];
            }
        }

        return $name;
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
        return null !== $name && '' !== $name
            ? $this->propertyAccessor->getValue($dataRow, $name)
            : null;
    }

    /**
     * Paginate the rows.
     *
     * @param array $pagination
     * @param int   $rowNumber
     *
     * @return array The paginated rows
     *
     * @throws InvalidConfigurationException When the block renderer is not injected with the "setRenderer" method
     */
    protected function paginateRows(array $pagination, $rowNumber)
    {
        if (null === $this->renderer) {
            throw new InvalidConfigurationException('The block renderer must be injected with "setRenderer" method');
        }

        $cacheRows = array();

        // loop in rows
        foreach ($pagination as $data) {
            $row = array(
                '_row_number' => $rowNumber++,
                '_attr_columns' => array(),
            );

            if (null !== $this->rowId) {
                $rowId = $this->getDataField($data, $this->rowId);

                if (null !== $rowId) {
                    $row['_row_id'] = $rowId;
                }
            }

            // loop in cells
            /* @var BlockInterface $column */
            foreach ($this->getColumns() as $column) {
                if ($column->hasOption('enabled') && false === $column->getOption('enabled')) {
                    continue;
                }

                if (count($column->getOption('attr')) > 0) {
                    $row['_attr_columns'][$column->getName()] = $column->getOption('attr');
                }

                if ('_row_number' === $column->getOption('index')) {
                    continue;
                }

                $config = $column->getConfig();
                $formatter = $config->getOption('formatter');
                $field = $config->getOption('data_property_path');
                $field = null === $field
                    ? $this->formatIndex($config->getOption('index'))
                    : $field;
                $cellData = $this->getDataField($data, $field);
                $options = array_replace(array('wrapped' => false, 'inherit_data' => false), $config->getOption('formatter_options'));

                if (TwigType::class === $formatter) {
                    $options = array_merge_recursive($options, array(
                        'variables' => array(
                            '_column' => $column,
                            '_row_data' => $data,
                            '_row_number' => $row['_row_number'],
                        ),
                    ));
                }

                /* @var BlockBuilderInterface $config */
                $cell = $config->getBlockFactory()->createNamed($column->getName(), $formatter, $cellData, $options);
                $row[$column->getName()] = $this->renderer->searchAndRenderBlock($cell->createView(), 'widget');
            }

            if (0 === count($row['_attr_columns'])) {
                unset($row['_attr_columns']);
            }

            $cacheRows[] = $row;
        }

        return $cacheRows;
    }
}
