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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Glyphicon Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class GlyphiconType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'icon' => $options['icon'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'icon' => null,
        ));

        $resolver->setAllowedTypes(array(
            'icon' => 'string',
        ));

        $resolver->setNormalizers(array(
            'icon' => function (Options $options, $value = null) {
                if (isset($options['data'])) {
                    return $options['data'];
                }

                return $value;
            },
        ));

        $resolver->setAllowedValues(array(
            'icon' => array(
                'adjust',
                'adjust',
                'align-center',
                'align-justify',
                'align-left',
                'align-right',
                'arrow-down',
                'arrow-left',
                'arrow-right',
                'arrow-up',
                'asterisk',
                'backward',
                'ban-circle',
                'barcode',
                'bell',
                'bold',
                'book',
                'bookmark',
                'briefcase',
                'bullhorn',
                'calendar',
                'camera',
                'certificate',
                'check',
                'chevron-down',
                'chevron-left',
                'chevron-right',
                'chevron-up',
                'circle-arrow-down',
                'circle-arrow-left',
                'circle-arrow-right',
                'circle-arrow-up',
                'cloud',
                'cloud-download',
                'cloud-upload',
                'cog',
                'collapse-down',
                'collapse-up',
                'comment',
                'compressed',
                'copyright-mark',
                'credit-card',
                'cutlery',
                'dashboard',
                'download',
                'download-alt',
                'earphone',
                'edit',
                'eject',
                'envelope',
                'euro',
                'exclamation-sign',
                'expand',
                'export',
                'eye-close',
                'eye-open',
                'facetime-video',
                'fast-backward',
                'fast-forward',
                'file',
                'film',
                'filter',
                'fire',
                'flag',
                'flash',
                'floppy-disk',
                'floppy-open',
                'floppy-remove',
                'floppy-save',
                'floppy-saved',
                'folder-close',
                'folder-open',
                'font',
                'forward',
                'fullscreen',
                'gbp',
                'gift',
                'glass',
                'globe',
                'hand-down',
                'hand-left',
                'hand-right',
                'hand-up',
                'hd-video',
                'hdd',
                'header',
                'headphones',
                'heart',
                'heart-empty',
                'home',
                'import',
                'inbox',
                'indent-left',
                'indent-right',
                'info-sign',
                'italic',
                'leaf',
                'link',
                'list',
                'list-alt',
                'lock',
                'log-in',
                'log-out',
                'magnet',
                'map-marker',
                'minus',
                'minus-sign',
                'move',
                'music',
                'new-window',
                'off',
                'ok',
                'ok-circle',
                'ok-sign',
                'open',
                'paperclip',
                'pause',
                'pencil',
                'phone',
                'phone-alt',
                'picture',
                'plane',
                'play',
                'play-circle',
                'plus',
                'plus-sign',
                'print',
                'pushpin',
                'qrcode',
                'question-sign',
                'random',
                'record',
                'refresh',
                'registration-mark',
                'remove',
                'remove-circle',
                'remove-sign',
                'repeat',
                'resize-full',
                'resize-horizontal',
                'resize-small',
                'resize-vertical',
                'retweet',
                'road',
                'save',
                'saved',
                'screenshot',
                'sd-video',
                'search',
                'send',
                'share',
                'share-alt',
                'shopping-cart',
                'signal',
                'sort',
                'sort-by-alphabet',
                'sort-by-alphabet-alt',
                'sort-by-attributes',
                'sort-by-attributes-alt',
                'sort-by-order',
                'sort-by-order-alt',
                'sound-5-1',
                'sound-6-1',
                'sound-7-1',
                'sound-dolby',
                'sound-stereo',
                'star',
                'star-empty',
                'stats',
                'step-backward',
                'step-forward',
                'stop',
                'subtitles',
                'tag',
                'tags',
                'tasks',
                'text-height',
                'text-width',
                'th',
                'th-large',
                'th-list',
                'thumbs-down',
                'thumbs-up',
                'time',
                'tint',
                'tower',
                'transfer',
                'trash',
                'tree-conifer',
                'tree-deciduous',
                'unchecked',
                'upload',
                'usd',
                'user',
                'volume-down',
                'volume-off',
                'volume-up',
                'warning-sign',
                'wrench',
                'zoom-in',
                'zoom-out',
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'glyphicon';
    }
}
