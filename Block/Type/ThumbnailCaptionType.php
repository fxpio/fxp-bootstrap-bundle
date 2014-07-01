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

/**
 * ThumbnailCaption Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ThumbnailCaptionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $child) {
            if (in_array('heading', $child->vars['block_prefixes'])) {
                $child->vars['size'] = 3;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'thumbnail_caption';
    }
}
