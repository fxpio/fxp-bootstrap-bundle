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
        $view->vars['money_pattern'] = self::getPattern($options['currency']);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'money';
    }

    /**
     * Returns the pattern for this locale
     *
     * The pattern contains the placeholder "{{ widget }}" where the HTML tag should
     * be inserted
     */
    protected static function getPattern($currency)
    {
        if (!$currency) {
            return '{{ widget }}';
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

            $start = '<span class="input-group-addon">';
            $end = '</span>';

            if (!empty($matches[1])) {
                self::$patterns[$locale][$currency] = $start.$matches[1].$end.'{{ widget }}';
            } elseif (!empty($matches[2])) {
                self::$patterns[$locale][$currency] = '{{ widget }}'.$start.$matches[2].$end;
            } else {
                self::$patterns[$locale][$currency] = '{{ widget }}';
            }
        }

        return self::$patterns[$locale][$currency];
    }
}
