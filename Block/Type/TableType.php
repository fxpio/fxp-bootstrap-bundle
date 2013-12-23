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
use Sonatra\Bundle\BlockBundle\Block\BlockFactoryInterface;
use Sonatra\Bundle\BlockBundle\Block\Extension\Core\DataMapper\WrapperMapper;
use Sonatra\Bundle\BootstrapBundle\Table\DataSource;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

/**
 * Table Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TableType extends AbstractType
{
    /**
     * @var BlockFactoryInterface
     */
    protected $factory;

    /**
     * Constructor.
     *
     * @param BlockFactoryInterface $factory
     */
    public function __construct(BlockFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper(new WrapperMapper());
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
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

        if (!isset($view->vars['header'])) {
            $blockHeader = $this->factory->createNamed('header', 'table_header', null, array());
            $view->vars['header'] = $blockHeader->createView($view);
        }

        $view->vars['header']->vars['header_columns'] = $columns;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'striped'    => false,
            'bordered'   => false,
            'condensed'  => false,
            'responsive' => false,
            'hover_rows' => false,
            'data'       => array(),
        ));

        $resolver->setAllowedTypes(array(
            'striped'    => 'bool',
            'bordered'   => 'bool',
            'condensed'  => 'bool',
            'responsive' => 'bool',
            'hover_rows' => 'bool',
            'data'       => array('array', 'Sonatra\Bundle\BootstrapBundle\Table\DataSourceInterface'),
        ));

        $resolver->setNormalizers(array(
            'data' => function (Options $options, $value) {
                if (is_array($value)) {
                    $data = new DataSource();
                    $data->setRows($value);

                    return $data;
                }

                return $value;
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'table';
    }
}
