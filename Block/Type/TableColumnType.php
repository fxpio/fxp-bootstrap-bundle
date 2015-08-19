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
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Table Column Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TableColumnType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'index' => $options['index'],
            'formatter' => $options['formatter'],
            'formatter_options' => $options['formatter_options'],
            'empty_data' => $options['empty_data'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $index = function (Options $options, $value) {
            if (null === $value) {
                $value = $options['block_name'];
            }

            return $value;
        };

        $resolver->setDefaults(array(
            'index' => $index,
            'formatter' => 'text',
            'formatter_options' => array(),
            'empty_data' => null,
        ));

        $resolver->setAllowedTypes('formatter', array('null', 'string'));
        $resolver->setAllowedTypes('formatter_options', 'array');

        $resolver->setNormalizer('formatter_options', function (Options $options, $value) {
            $value['empty_data'] = $options['empty_data'];
            $value['empty_message'] = $options['empty_message'];

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'table_column';
    }
}
