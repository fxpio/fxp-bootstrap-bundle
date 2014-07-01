<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Block\Type;

use Sonatra\Bundle\BlockBundle\Block\AbstractType;
use Sonatra\Bundle\BlockBundle\Block\BlockBuilderInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockRendererInterface;
use Sonatra\Bundle\BlockBundle\Block\ResolvedBlockTypeInterface;
use Sonatra\Bundle\BlockBundle\Block\Util\BlockUtil;
use Sonatra\Bundle\BootstrapBundle\Block\DataSource\DataSource;
use Sonatra\Bundle\BootstrapBundle\Block\DataSource\DataSourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Table Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TableType extends AbstractType
{
    /**
     * @var BlockRendererInterface
     */
    protected $renderer;

    /**
     * Constructor.
     *
     * @param BlockRendererInterface $renderer
     */
    public function __construct(BlockRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if (is_array($builder->getData())) {
            $source = new DataSource($options['row_id']);
            $source->setPageSizeMax($options['page_size_max']);
            $source->setPageSize($options['page_size']);
            $source->setRows($builder->getData());
            $source->setLocale($options['locale']);
            $source->setSortColumns($options['sort_columns']);
            $source->setParameters($options['data_parameters']);
            $source->setPageNumber($options['page_number']);

            $builder->setData($source);
            $builder->setDataClass(get_class($source));
        }

        $builder->add('_header', 'table_header');
    }

    /**
     * {@inheritdoc}
     */
    public function finishBlock(BlockBuilderInterface $builder, array $options)
    {
        if ($builder->getData() instanceof DataSourceInterface) {
            $builder->getData()->setRenderer($this->renderer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (BlockUtil::isValidBlock('table_header', $child)) {
            if ($block->has('_header')) {
                $block->remove('_header');
            }
        } elseif ($this->isColumn($child->getConfig()->getType())) {
            $block->getData()->addColumn($child);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if ($this->isColumn($child->getConfig()->getType())) {
            $block->getData()->removeColumn($child->getName());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $block->getData()->setTableView($view);

        $view->vars = array_replace($view->vars, array(
            'striped'    => $options['striped'],
            'bordered'   => $options['bordered'],
            'condensed'  => $options['condensed'],
            'responsive' => $options['responsive'],
            'hover_rows' => $options['hover_rows'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        $columns = array();

        foreach ($view->children as $name => $child) {
            if (in_array('table_caption', $child->vars['block_prefixes'])) {
                $view->vars['caption'] = $child;
                unset($view->children[$name]);

            } elseif (in_array('table_header', $child->vars['block_prefixes'])) {
                $view->vars['header'] = $child;
                unset($view->children[$name]);

            } elseif (in_array('table_footer', $child->vars['block_prefixes'])) {
                $view->vars['footer'] = $child;
                unset($view->children[$name]);

            } elseif (in_array('table_column', $child->vars['block_prefixes'])) {
                $columns[] = $child;
                unset($view->children[$name]);
            }
        }

        $view->vars['header']->vars['header_columns'] = $columns;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'striped'         => false,
            'bordered'        => false,
            'condensed'       => false,
            'responsive'      => false,
            'hover_rows'      => false,
            'data'            => array(),
            'locale'          => \Locale::getDefault(),
            'page_size'       => 0,
            'page_size_max'   => 2000,
            'page_start'      => 1,
            'page_number'     => 1,
            'sort_columns'    => array(),
            'data_parameters' => array(),
            'row_id'          => 'id',
        ));

        $resolver->setAllowedTypes(array(
            'striped'         => 'bool',
            'bordered'        => 'bool',
            'condensed'       => 'bool',
            'responsive'      => 'bool',
            'hover_rows'      => 'bool',
            'data'            => array('array', 'Sonatra\Bundle\BootstrapBundle\Block\DataSource\DataSourceInterface'),
            'locale'          => 'string',
            'page_size'       => 'int',
            'page_start'      => 'int',
            'page_number'     => 'int',
            'sort_columns'    => 'array',
            'data_parameters' => 'array',
            'row_id'          => 'string',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'table';
    }

    /**
     * Check if the child is a column.
     *
     * @param ResolvedBlockTypeInterface $type
     *
     * @return boolean
     */
    protected function isColumn(ResolvedBlockTypeInterface $type)
    {
        if ('table_column' === $type->getName()) {
            return true;
        }

        if (null !== $type->getParent()) {
            return $this->isColumn($type->getParent());
        }

        return false;
    }
}
