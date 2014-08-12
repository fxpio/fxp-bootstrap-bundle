<?php

/*
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
 * Token Parser for the 'assetic' tag.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class AsseticTokenParser extends \Twig_TokenParser
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
     * Constructor.
     *
     * @param AssetManager $manager The assetic manager
     * @param string       $tag     The tag name
     */
    public function __construct(AssetManager $manager, $tag)
    {
        $this->manager = $manager;
        $this->tag = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $stream = $this->parser->getStream();
        list($name, $attributes) = Util::getAsseticConfig($stream);

        if (null === $name) {
            throw new \Twig_Error_Syntax('The name of asset must be present');
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse(array($this, 'testEndTag'), true);

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

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
}
