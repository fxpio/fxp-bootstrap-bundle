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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

/**
 * Email Form Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class EmailExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $prependNormalizers = function (Options $options, $value) {
            if (null === $value) {
                $value = '@';
            }

            if ('' === $value) {
                $value = null;
            }

            return $value;
        };

        $resolver->setNormalizers(array(
            'prepend' => $prependNormalizers,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'email';
    }
}
