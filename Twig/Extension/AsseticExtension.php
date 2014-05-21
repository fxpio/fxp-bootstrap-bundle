<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sonatra\Bundle\BootstrapBundle\Twig\TokenParser\AsseticTokenParser;
use Sonatra\Bundle\BootstrapBundle\Twig\TokenParser\AsseticLocalizedTokenParser;

/**
 * BlockExtension extends Twig with block capabilities.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class AsseticExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        $manager = $this->container->get('assetic.asset_manager');

        return array(
            new AsseticTokenParser($manager, 'assetjavascripts'),
            new AsseticTokenParser($manager, 'assetstylesheets'),
            new AsseticLocalizedTokenParser($manager, 'localizedassetjavascripts', 'javascript'),
            new AsseticLocalizedTokenParser($manager, 'localizedassetstylesheets', 'stylesheet'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonatra_assetic';
    }
}
