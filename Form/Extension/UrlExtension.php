<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Url Form Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class UrlExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $prependNormalizers = function (Options $options, $value) {
            if (null === $value) {
                $value = $options['default_protocol'].'://';
            }

            if ('' === $value) {
                $value = null;
            }

            return $value;
        };

        $resolver->setNormalizer('prepend', $prependNormalizers);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'url';
    }
}
