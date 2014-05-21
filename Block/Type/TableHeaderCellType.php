<?php

/**
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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Table Header Cell Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TableHeaderCellType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'rowspan' => $options['rowspan'],
            'colspan' => $options['colspan'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'rowspan' => null,
            'colspan' => null,
        ));

        $resolver->setAllowedTypes(array(
            'rowspan' => array('null', 'int'),
            'colspan' => array('null', 'int'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'table_header_cell';
    }
}
