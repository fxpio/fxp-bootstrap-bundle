<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Block\Extension;

use Sonatra\Bundle\BlockBundle\Block\AbstractTypeExtension;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use Sonatra\Bundle\BlockBundle\Block\Exception\InvalidConfigurationException;

/**
 * Responsive Utilities Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ResponsiveExtension extends AbstractTypeExtension
{
    /**
     * @var array
     */
    private $validVisible = array(
        'xs-block',
        'xs-inline',
        'xs-inline-block',
        'sm-block',
        'sm-inline',
        'sm-inline-block',
        'md-block',
        'md-inline',
        'md-inline-block',
        'lg-block',
        'lg-inline',
        'lg-inline-block',
        'print-block',
        'print-inline',
        'print-inline-block',
    );

    /**
     * @var array
     */
    private $validHidden = array('xs', 'sm', 'md', 'lg', 'print');

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'visible_viewport' => implode(' ', $options['visible_viewport']),
            'hidden_viewport'  => implode(' ', $options['hidden_viewport']),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'visible_viewport' => null,
            'hidden_viewport'  => null,
        ));

        $resolver->setAllowedTypes(array(
            'visible_viewport' => array('null', 'string', 'array'),
            'hidden_viewport'  => array('null', 'string', 'array'),
        ));

        $resolver->setNormalizers(array(
            'visible_viewport' => function (Options $options, $value = null) {
                return $this->normaliseViewport('visible', $this->validVisible, $value);
            },
            'hidden_viewport' => function (Options $options, $value = null) {
                return $this->normaliseViewport('hidden', $this->validHidden, $value);
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'block';
    }

    /**
     * Normalises the viewport.
     *
     * @param string      $prefix
     * @param array       $valid
     * @param string|null $value
     *
     * @return array The valid formatted viewport
     *
     * @throws InvalidConfigurationException When viewport value does not exist
     */
    protected function normaliseViewport($prefix, array $valid, $value)
    {
        $value = $this->convertToArray($value);

        foreach ($value as $i => $viewport) {
            if (!in_array($viewport, $valid)) {
                throw new InvalidConfigurationException(sprintf('The "%s" %s viewport option does not exist. Known options are: "%s"', $viewport, $prefix, implode('", "', $valid)));
            }

            $value[$i] = sprintf('%s-%s', $prefix, $viewport);
        }

        return $value;
    }

    /**
     * Convert value to array.
     *
     * @param array|string|null $value
     *
     * @return array
     */
    protected function convertToArray($value)
    {
        if (is_string($value)) {
            $value = array($value);

        } elseif (null === $value) {
            $value = array();
        }

        return $value;
    }
}
