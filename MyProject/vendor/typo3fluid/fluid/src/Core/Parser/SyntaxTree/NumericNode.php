<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser\SyntaxTree;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Numeric Syntax Tree Node - is a container for numeric values.
 *
 * @internal
 * @todo Make class final.
 */
class NumericNode extends AbstractNode
{
    /**
     * Contents of the numeric node
     * @var number
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param string|number $value value to store in this numericNode
     * @throws Parser\Exception
     */
    public function __construct($value)
    {
        if (!is_numeric($value)) {
            throw new Parser\Exception('Numeric node requires an argument of type number, "' . gettype($value) . '" given.');
        }
        $this->value = $value + 0;
    }

    /**
     * Return the value associated to the syntax tree.
     *
     * @param RenderingContextInterface $renderingContext
     * @return number the value stored in this node/subtree.
     */
    public function evaluate(RenderingContextInterface $renderingContext)
    {
        return $this->value;
    }

    /**
     * Getter for value
     *
     * @return number The value of this node
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * NumericNode does not allow adding child nodes, so this will always throw an exception.
     *
     * @param NodeInterface $childNode The sub node to add
     * @throws Parser\Exception
     */
    public function addChildNode(NodeInterface $childNode)
    {
        throw new Parser\Exception('Numeric nodes may not contain child nodes, tried to add "' . get_class($childNode) . '".');
    }

    public function convert(TemplateCompiler $templateCompiler): array
    {
        return [
            'initialization' => '',
            'execution' => $this->value,
        ];
    }
}
