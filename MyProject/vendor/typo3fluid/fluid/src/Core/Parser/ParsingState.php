<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser;

use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\AbstractNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\NodeInterface;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\RootNode;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3Fluid\Fluid\View;

/**
 * Stores all information relevant for one parsing pass - that is, the root node,
 * and the current stack of open nodes (nodeStack) and a variable container used
 * for PostParseFacets.
 */
class ParsingState implements ParsedTemplateInterface
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * Root node reference
     *
     * @var RootNode
     */
    protected $rootNode;

    /**
     * Array of node references currently open.
     *
     * @var array
     */
    protected $nodeStack = [];

    /**
     * Variable container where ViewHelpers implementing the PostParseFacet can
     * store things in.
     *
     * @var VariableProviderInterface
     */
    protected $variableContainer;

    /**
     * The layout name of the current template or NULL if the template does not contain a layout definition
     *
     * @var AbstractNode
     */
    protected $layoutNameNode;

    /**
     * @var bool
     */
    protected $compilable = true;

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Injects a variable container to be used during parsing.
     *
     * @param VariableProviderInterface $variableContainer
     */
    public function setVariableProvider(VariableProviderInterface $variableContainer)
    {
        $this->variableContainer = $variableContainer;
    }

    /**
     * Set root node of this parsing state.
     *
     * @param NodeInterface $rootNode
     */
    public function setRootNode(RootNode $rootNode)
    {
        $this->rootNode = $rootNode;
    }

    /**
     * Get root node of this parsing state.
     *
     * @return NodeInterface The root node
     */
    public function getRootNode()
    {
        return $this->rootNode;
    }

    /**
     * Render the parsed template with rendering context
     *
     * @param RenderingContextInterface $renderingContext The rendering context to use
     * @return string Rendered string
     */
    public function render(RenderingContextInterface $renderingContext)
    {
        return $this->getRootNode()->evaluate($renderingContext);
    }

    /**
     * Push a node to the node stack. The node stack holds all currently open
     * templating tags.
     *
     * @param NodeInterface $node Node to push to node stack
     */
    public function pushNodeToStack(NodeInterface $node)
    {
        $this->nodeStack[] = $node;
    }

    /**
     * Get the top stack element, without removing it.
     *
     * @return NodeInterface the top stack element.
     */
    public function getNodeFromStack()
    {
        return $this->nodeStack[count($this->nodeStack) - 1];
    }

    /**
     * Pop the top stack element (=remove it) and return it back.
     *
     * @return NodeInterface the top stack element, which was removed.
     */
    public function popNodeFromStack()
    {
        return array_pop($this->nodeStack);
    }

    /**
     * Count the size of the node stack
     *
     * @return int Number of elements on the node stack (i.e. number of currently open Fluid tags)
     */
    public function countNodeStack()
    {
        return count($this->nodeStack);
    }

    /**
     * Returns a variable container which will be then passed to the postParseFacet.
     *
     * @return VariableProviderInterface The variable container or NULL if none has been set yet
     */
    public function getVariableContainer()
    {
        return $this->variableContainer;
    }

    /**
     * Returns TRUE if the current template has a template defined via <f:layout name="..." />
     *
     * @return bool
     */
    public function hasLayout()
    {
        return $this->variableContainer->exists('layoutName');
    }

    /**
     * Returns the name of the layout that is defined within the current template via <f:layout name="..." />
     * If no layout is defined, this returns NULL
     * This requires the current rendering context in order to be able to evaluate the layout name
     *
     * @param RenderingContextInterface $renderingContext
     * @return string|null
     * @throws View\Exception
     */
    public function getLayoutName(RenderingContextInterface $renderingContext)
    {
        $layoutName = $this->variableContainer->get('layoutName');
        return $layoutName instanceof RootNode ? $layoutName->evaluate($renderingContext) : $layoutName;
    }

    /**
     * @param RenderingContextInterface $renderingContext
     */
    public function addCompiledNamespaces(RenderingContextInterface $renderingContext)
    {
    }

    /**
     * @return bool
     */
    public function isCompilable()
    {
        return $this->compilable;
    }

    /**
     * @param bool $compilable
     */
    public function setCompilable($compilable)
    {
        $this->compilable = $compilable;
    }

    /**
     * @return bool
     */
    public function isCompiled()
    {
        return false;
    }
}
