<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser\Interceptor;

use TYPO3Fluid\Fluid\Core\Parser\InterceptorInterface;
use TYPO3Fluid\Fluid\Core\Parser\ParsingState;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\EscapingNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\ExpressionNodeInterface;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\NodeInterface;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;

/**
 * An interceptor adding the "Htmlspecialchars" viewhelper to the suitable places.
 */
class Escape implements InterceptorInterface
{
    /**
     * Is the interceptor enabled right now for child nodes?
     *
     * @var bool
     */
    protected $childrenEscapingEnabled = true;

    /**
     * A stack of ViewHelperNodes which currently disable the interceptor.
     * Needed to enable the interceptor again.
     *
     * @var NodeInterface[]
     */
    protected $viewHelperNodesWhichDisableTheInterceptor = [];

    /**
     * Adds a ViewHelper node using the Format\HtmlspecialcharsViewHelper to the given node.
     * If "escapingInterceptorEnabled" in the ViewHelper is FALSE, will disable itself inside the ViewHelpers body.
     *
     * @param NodeInterface $node
     * @param int $interceptorPosition One of the INTERCEPT_* constants for the current interception point
     * @param ParsingState $parsingState the current parsing state. Not needed in this interceptor.
     * @return NodeInterface
     */
    public function process(NodeInterface $node, $interceptorPosition, ParsingState $parsingState)
    {
        if ($interceptorPosition === InterceptorInterface::INTERCEPT_OPENING_VIEWHELPER) {
            /** @var ViewHelperNode $node */
            if (!$node->getUninitializedViewHelper()->isChildrenEscapingEnabled()) {
                $this->childrenEscapingEnabled = false;
                $this->viewHelperNodesWhichDisableTheInterceptor[] = $node;
            }
        } elseif ($interceptorPosition === InterceptorInterface::INTERCEPT_CLOSING_VIEWHELPER) {
            if (end($this->viewHelperNodesWhichDisableTheInterceptor) === $node) {
                array_pop($this->viewHelperNodesWhichDisableTheInterceptor);
                if (count($this->viewHelperNodesWhichDisableTheInterceptor) === 0) {
                    $this->childrenEscapingEnabled = true;
                }
            }
            /** @var ViewHelperNode $node */
            if ($this->childrenEscapingEnabled && $node->getUninitializedViewHelper()->isOutputEscapingEnabled()) {
                $node = new EscapingNode($node);
            }
        } elseif ($this->childrenEscapingEnabled && ($node instanceof ObjectAccessorNode || $node instanceof ExpressionNodeInterface)) {
            $node = new EscapingNode($node);
        }
        return $node;
    }

    /**
     * This interceptor wants to hook into object accessor creation, and opening / closing ViewHelpers.
     *
     * @return array Array of INTERCEPT_* constants
     */
    public function getInterceptionPoints()
    {
        return [
            InterceptorInterface::INTERCEPT_OPENING_VIEWHELPER,
            InterceptorInterface::INTERCEPT_CLOSING_VIEWHELPER,
            InterceptorInterface::INTERCEPT_OBJECTACCESSOR,
            InterceptorInterface::INTERCEPT_EXPRESSION,
        ];
    }
}
