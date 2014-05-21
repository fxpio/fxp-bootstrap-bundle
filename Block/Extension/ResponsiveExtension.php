<?php

/**
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
    private $validPrefix = array('xs', 'sm', 'md', 'lg');

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'visibility' => $options['visibility'],
            'print'      => $options['print'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'visibility' => null,
            'print'      => null,
        ));

        $resolver->setAllowedTypes(array(
            'visibility' => array('null', 'string', 'array'),
            'print'      => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'print' => array('visible', 'hidden'),
        ));

        $resolver->setNormalizers(array(
            'visibility' => function (Options $options, $value = null) {
                $value = $this->convertToArray($value);

                foreach ($value as $i => $visibility) {
                    if (!in_array($visibility, $this->validPrefix)) {
                        throw new InvalidConfigurationException(sprintf('The "%s" prefix visibility option does not exist. Known options are: "%s"', $visibility, implode('", "', $this->validPrefix)));
                    }

                    $value[$i] = sprintf('visibility-%s', $visibility);
                }

                return $value;
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
