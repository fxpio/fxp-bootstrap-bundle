<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Twig\TokenParser;

use Assetic\AssetManager;
use Sonatra\Bundle\BootstrapBundle\Twig\Node\AsseticNode;

/**
 * Token Parser for the 'localized assetic' tag.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class AsseticLocalizedTokenParser extends \Twig_TokenParser
{
    /**
     * @var AssetManager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $tag;

    /**
     * @var string
     */
    protected $type;

    /**
     * Constructor.
     *
     * @param AssetManager $manager The assetic manager
     * @param string       $tag     The tag name
     * @param string       $type    The asset type
     */
    public function __construct(AssetManager $manager, $tag, $type)
    {
        $this->manager = $manager;
        $this->tag = $tag;
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $stream = $this->parser->getStream();
        $locale = null;
        $attributes = array(
            'var_name' => 'asset_url',
            'vars'     => array(),
        );

        while (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->test(\Twig_Token::STRING_TYPE)) {
                // 'en'
                $locale = $stream->next()->getValue();

            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'as')) {
                // as='the_url'
                $stream->next();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $attributes['var_name'] = $stream->expect(\Twig_Token::STRING_TYPE)->getValue();

            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'debug')) {
                // debug=true
                $stream->next();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $attributes['debug'] = 'true' == $stream->expect(\Twig_Token::NAME_TYPE, array('true', 'false'))->getValue();

            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'combine')) {
                // combine=true
                $stream->next();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $attributes['combine'] = 'true' == $stream->expect(\Twig_Token::NAME_TYPE, array('true', 'false'))->getValue();

            } else {
                $token = $stream->getCurrent();

                throw new \Twig_Error_Syntax(sprintf('Unexpected token "%s" of value "%s"', \Twig_Token::typeToEnglish($token->getType(), $token->getLine()), $token->getValue()), $token->getLine());
            }
        }

        if (null === $locale) {
            $locale = \Locale::getDefault();
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse(array($this, 'testEndTag'), true);

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $name = $this->getAsseticName($locale);

        if (null === $name && $locale !== \Locale::getDefault()) {
            $name = $this->getAsseticName(\Locale::getDefault());
        }

        if (null === $name) {
            return new \Twig_Node_Text('', $token->getLine());
        }

        $asset = $this->manager->get($name);

        return new AsseticNode($asset, $body, $name, $attributes, $token->getLine(), $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Test end tag.
     *
     * @param \Twig_Token $token
     *
     * @return boolean
     */
    public function testEndTag(\Twig_Token $token)
    {
        return $token->test(array('end'.$this->getTag()));
    }

    /**
     * Get the service id of common localized asset, with fallback on global
     * localization (ex. en_US => en)
     *
     * @param string $locale The locale
     *
     * @return string|null
     */
    protected function getAsseticName($locale)
    {
        $name = 'sonatra_bootstrap_common_localized_%s_%ss';
        $locale = str_replace('-', '_', $locale);
        $locale = strtolower($locale);
        $assetName = sprintf($name, $locale, $this->type);

        if ($this->manager->has($assetName)) {
            return $assetName;
        }

        if (false === strpos($locale, '_')) {
            return null;
        }

        $locale = substr($locale, 0, strpos($locale, '_'));
        $assetName = sprintf($name, $locale, $this->type);

        if ($this->manager->has($assetName)) {
            return $assetName;
        }

        return null;
    }
}
