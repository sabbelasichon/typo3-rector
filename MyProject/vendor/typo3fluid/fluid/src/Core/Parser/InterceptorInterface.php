<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser;

use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\NodeInterface;

/**
 * An interceptor interface. Interceptors are used in the parsing stage to change
 * the syntax tree of a template, e.g. by adding viewhelper nodes.
 */
interface InterceptorInterface
{
    public const INTERCEPT_OPENING_VIEWHELPER = 1;
    public const INTERCEPT_CLOSING_VIEWHELPER = 2;
    public const INTERCEPT_TEXT = 3;
    public const INTERCEPT_OBJECTACCESSOR = 4;
    public const INTERCEPT_EXPRESSION = 5;

    /**
     * The interceptor can process the given node at will and must return a node
     * that will be used in place of the given node.
     *
     * @param NodeInterface $node
     * @param int $interceptorPosition One of the INTERCEPT_* constants for the current interception point
     * @param ParsingState $parsingState the parsing state
     * @return NodeInterface
     */
    public function process(NodeInterface $node, $interceptorPosition, ParsingState $parsingState);

    /**
     * The interceptor should define at which interception positions it wants to be called.
     *
     * @return array Array of INTERCEPT_* constants
     */
    public function getInterceptionPoints();
}
