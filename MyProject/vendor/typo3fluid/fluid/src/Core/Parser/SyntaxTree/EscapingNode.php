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
 * Escaping Node - wraps all content that must be escaped before output.
 *
 * @internal
 * @todo Make class final.
 */
class EscapingNode extends AbstractNode
{
    /**
     * Node to be escaped
     */
    protected NodeInterface $node;

    /**
     * Constructor.
     *
     * @param NodeInterface $node
     */
    public function __construct(NodeInterface $node)
    {
        $this->node = $node;
    }

    /**
     * Return the value associated to the syntax tree.
     *
     * @param RenderingContextInterface $renderingContext
     * @return mixed the value stored in this node/subtree.
     */
    public function evaluate(RenderingContextInterface $renderingContext)
    {
        $evaluated = $this->node->evaluate($renderingContext);
        if (is_string($evaluated) || (is_object($evaluated) && method_exists($evaluated, '__toString'))) {
            return htmlspecialchars((string)$evaluated, ENT_QUOTES);
        }
        return $evaluated;
    }

    public function getNode(): NodeInterface
    {
        return $this->node;
    }

    /**
     * NumericNode does not allow adding child nodes, so this will always throw an exception.
     *
     * @param NodeInterface $childNode The sub node to add
     * @throws Parser\Exception
     */
    public function addChildNode(NodeInterface $childNode)
    {
        $this->node = $childNode;
    }

    public function convert(TemplateCompiler $templateCompiler): array
    {
        $configuration = $this->getNode()->convert($templateCompiler);
        if ($configuration['execution'] !== '\'\'') {
            $configuration['execution'] = sprintf(
                'call_user_func_array( function ($var) { ' .
                'return (is_string($var) || (is_object($var) && method_exists($var, \'__toString\')) ' .
                '? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [%s])',
                $configuration['execution']
            );
        }
        return $configuration;
    }
}
