<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Twig\Node;

use Assetic\Asset\AssetInterface;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class AsseticNode extends \Twig_Node
{
    /**
     * Constructor.
     *
     * Available attributes:
     *
     *  * debug:    The debug mode
     *  * combine:  Whether to combine assets
     *  * var_name: The name of the variable to expose to the body node
     *
     * @param AssetInterface $asset      The asset
     * @param \Twig_Node     $body       The body node
     * @param string         $name       The name of the asset
     * @param array          $attributes An array of attributes
     * @param integer        $lineno     The line number
     * @param string         $tag        The tag name
     */
    public function __construct(AssetInterface $asset, \Twig_Node $body, $name, array $attributes = array(), $lineno = 0, $tag = null)
    {
        $nodes = array('body' => $body);

        $attributes = array_replace(
            array('debug' => null, 'combine' => null, 'var_name' => 'asset_url'),
            $attributes,
            array('asset' => $asset, 'inputs' => array(), 'filters' => array(), 'name' => $name)
        );

        parent::__construct($nodes, $attributes, $lineno, $tag);
    }

    /**
     * {@inheritdoc}
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $combine = $this->getAttribute('combine');
        $debug = $this->getAttribute('debug');

        if (null === $combine && null !== $debug) {
            $combine = !$debug;
        }

        if (null === $combine) {
            $compiler
                ->write("if (isset(\$context['assetic']['debug']) && \$context['assetic']['debug']) {\n")
                ->indent()
            ;

            $this->compileDebug($compiler);

            $compiler
                ->outdent()
                ->write("} else {\n")
                ->indent()
            ;

            $this->compileAsset($compiler, $this->getAttribute('asset'), $this->getAttribute('name'));

            $compiler
                ->outdent()
                ->write("}\n")
            ;

        } elseif ($combine) {
            $this->compileAsset($compiler, $this->getAttribute('asset'), $this->getAttribute('name'));

        } else {
            $this->compileDebug($compiler);
        }

        $compiler
            ->write('unset($context[')
            ->repr($this->getAttribute('var_name'))
            ->raw("]);\n")
        ;
    }

    /**
     * Compile in debug mod.
     *
     * @param \Twig_Compiler $compiler
     */
    protected function compileDebug(\Twig_Compiler $compiler)
    {
        $i = 0;

        foreach ($this->getAttribute('asset') as $leaf) {
            $leafName = $this->getAttribute('name').'_'.$i++;
            $this->compileAsset($compiler, $leaf, $leafName);
        }
    }

    /**
     * Compile the asset.
     *
     * @param \Twig_Compiler $compiler
     * @param AssetInterface $asset
     * @param string         $name
     */
    protected function compileAsset(\Twig_Compiler $compiler, AssetInterface $asset, $name)
    {
        $compiler
            ->write("// asset \"$name\"\n")
            ->write('$context[')
            ->repr($this->getAttribute('var_name'))
            ->raw('] = ')
        ;

        $this->compileAssetUrl($compiler, $asset, $name);

        $compiler
            ->raw(";\n")
            ->subcompile($this->getNode('body'))
        ;
    }

    /**
     * Compile the asset url.
     *
     * @param \Twig_Compiler $compiler
     * @param AssetInterface $asset
     * @param string         $name
     */
    protected function compileAssetUrl(\Twig_Compiler $compiler, AssetInterface $asset, $name)
    {
        $compiler
            ->raw('isset($context[\'assetic\'][\'use_controller\']) && $context[\'assetic\'][\'use_controller\'] ? ')
            ->subcompile($this->getPathFunction($name))
            ->raw(' : ')
            ->subcompile($this->getAssetFunction(new TargetPathNode($this, $asset, $name)))
        ;
    }

    /**
     * Get Path function.
     *
     * @param string $name The name of asset
     *
     * @return \Twig_Node_Expression_Function
     */
    private function getPathFunction($name)
    {
        $nodes = array(new \Twig_Node_Expression_Constant('_assetic_'.$name, $this->getLine()));

        return new \Twig_Node_Expression_Function('path', new \Twig_Node($nodes), $this->getLine());
    }

    /**
     * Get asset function.
     *
     * @param AsseticNode $path
     *
     * @return \Twig_Node_Expression_Function
     */
    private function getAssetFunction(AsseticNode $path)
    {
        return new \Twig_Node_Expression_Function('asset', new \Twig_Node(array($path)), $this->getLine());
    }
}
