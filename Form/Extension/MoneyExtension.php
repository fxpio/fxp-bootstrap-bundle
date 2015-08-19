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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Money Form Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MoneyExtension extends AbstractTypeExtension
{
    protected static $patterns = array();

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        list($pattern, $position, $currency) = self::getPattern($options['currency']);

        if ('prepend' === $position) {
            $view->vars['prepend_string'] = $currency;
            unset($view->vars['prepend_form']);
            unset($view->vars['prepend_block']);
        } elseif ('append' === $position) {
            $view->vars['append_string'] = $currency;
            unset($view->vars['append_form']);
            unset($view->vars['append_block']);
        }

        $view->vars['money_pattern'] = $pattern;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->addAllowedTypes(array(
            'prepend' => array('null'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'money';
    }

    /**
     * Returns the pattern/position/currency for this locale.
     *
     * The pattern contains the placeholder "{{ widget }}" where the HTML tag should
     * be inserted.
     *
     * @param string $currency
     *
     * @return array The pattern, position and currency variables
     */
    protected static function getPattern($currency)
    {
        if (!$currency) {
            return array('{{ widget }}', null, null);
        }

        $locale = \Locale::getDefault();

        if (!isset(self::$patterns[$locale])) {
            self::$patterns[$locale] = array();
        }

        if (!isset(self::$patterns[$locale][$currency])) {
            $format = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
            $pattern = $format->formatCurrency('123', $currency);

            // the spacings between currency symbol and number are ignored, because
            // a single space leads to better readability in combination with input
            // fields

            // the regex also considers non-break spaces (0xC2 or 0xA0 in UTF-8)

            preg_match('/^([^\s\xc2\xa0]*)[\s\xc2\xa0]*123(?:[,.]0+)?[\s\xc2\xa0]*([^\s\xc2\xa0]*)$/u', $pattern, $matches);

            if (!empty($matches[1])) {
                self::$patterns[$locale][$currency] = array('{{ widget }}', 'prepend', $matches[1]);
            } elseif (!empty($matches[2])) {
                self::$patterns[$locale][$currency] = array('{{ widget }}', 'append', $matches[2]);
            } else {
                self::$patterns[$locale][$currency] = array('{{ widget }}', null, null);
            }
        }

        return self::$patterns[$locale][$currency];
    }
}
