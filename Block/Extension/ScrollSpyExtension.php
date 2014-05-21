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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Scroll Spy Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ScrollSpyExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $scrollSpy = $options['scroll_spy'];

        if (null !== $scrollSpy['on']) {
            $scrollSpy['target'] = $view->vars['id'];

            $view->vars = array_replace($view->vars, array(
                'scroll_spy' => $scrollSpy,
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'scroll_spy' => array(),
        ));

        $resolver->addAllowedTypes(array(
            'scroll_spy' => 'array',
        ));

        $resolver->setNormalizers(array(
            'scroll_spy' => function (Options $options, $value) {
                $scrollSpyResolver = new OptionsResolver();

                $scrollSpyResolver->setDefaults(array(
                    'spy'    => 'scroll',
                    'on'     => null,
                    'offset' => null,
                ));

                $scrollSpyResolver->setAllowedTypes(array(
                    'spy'    => 'string',
                    'on'     => array('null', 'string'),
                    'offset' => array('null', 'int'),
                ));

                return $scrollSpyResolver->resolve($value);
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
}
