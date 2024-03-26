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
 * Abstract node in the syntax tree which has been built.
 */
abstract class AbstractNode implements NodeInterface
{
    /**
     * List of Child Nodes.
     *
     * @var NodeInterface[]
     */
    protected $childNodes = [];

    /**
     * Evaluate all child nodes and return the evaluated results.
     *
     * @param RenderingContextInterface $renderingContext
     * @return mixed Normally, an object is returned - in case it is concatenated with a string, a string is returned.
     * @throws Parser\Exception
     */
    public function evaluateChildNodes(RenderingContextInterface $renderingContext)
    {
        $evaluatedNodes = [];
        foreach ($this->getChildNodes() as $childNode) {
            $evaluatedNodes[] = $this->evaluateChildNode($childNode, $renderingContext, false);
        }
        // Make decisions about what to actually return
        if (empty($evaluatedNodes)) {
            return null;
        }
        if (count($evaluatedNodes) === 1) {
            return $evaluatedNodes[0];
        }
        return implode('', array_map([$this, 'castToString'], $evaluatedNodes));
    }

    /**
     * @param NodeInterface $node
     * @param RenderingContextInterface $renderingContext
     * @param bool $cast
     * @return mixed
     */
    protected function evaluateChildNode(NodeInterface $node, RenderingContextInterface $renderingContext, $cast)
    {
        $output = $node->evaluate($renderingContext);
        if ($cast) {
            $output = $this->castToString($output);
        }
        return $output;
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function castToString($value)
    {
        if (is_object($value) && !method_exists($value, '__toString')) {
            throw new Parser\Exception('Cannot cast object of type "' . get_class($value) . '" to string.', 1273753083);
        }
        $output = (string)$value;
        return $output;
    }

    /**
     * Returns all child nodes for a given node.
     * This is especially needed to implement the boolean expression language.
     *
     * @return NodeInterface[] A list of nodes
     */
    public function getChildNodes()
    {
        return $this->childNodes;
    }

    /**
     * Appends a sub node to this node. Is used inside the parser to append children
     *
     * @param NodeInterface $childNode The sub node to add
     */
    public function addChildNode(NodeInterface $childNode)
    {
        $this->childNodes[] = $childNode;
    }

    /**
     * General implementation. Nodes that actually create output
     * in compiled templates typically override this method.
     */
    public function convert(TemplateCompiler $templateCompiler): array
    {
        return [
            'initialization' => '// Uncompilable node type: ' . get_class($this) . chr(10),
            'execution' => ''
        ];
    }
}
