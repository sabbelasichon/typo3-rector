<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use PhpParser\Node\Expr\MethodCall;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class FluentChainMethodCallNodeAnalyzer
{
    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;

    public function __construct(NodeNameResolver $nodeNameResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
    }

    /**
     * @return string[]
     */
    public function collectMethodCallNamesInChain(MethodCall $desiredMethodCall): array
    {
        $methodCalls = $this->collectAllMethodCallsInChain($desiredMethodCall);

        $methodNames = [];
        foreach ($methodCalls as $methodCall) {
            $methodName = $this->nodeNameResolver->getName($methodCall->name);
            if ($methodName === null) {
                continue;
            }

            $methodNames[] = $methodName;
        }

        return $methodNames;
    }

    /**
     * @return MethodCall[]
     */
    public function collectAllMethodCallsInChain(MethodCall $methodCall): array
    {
        $chainMethodCalls = [$methodCall];

        // traverse up
        $currentNode = $methodCall->var;
        while ($currentNode instanceof MethodCall) {
            $chainMethodCalls[] = $currentNode;
            $currentNode = $currentNode->var;
        }

        // traverse down
        if (count($chainMethodCalls) === 1) {
            $currentNode = $methodCall->getAttribute(AttributeKey::PARENT_NODE);
            while ($currentNode instanceof MethodCall) {
                $chainMethodCalls[] = $currentNode;
                $currentNode = $currentNode->getAttribute(AttributeKey::PARENT_NODE);
            }
        }

        return $chainMethodCalls;
    }
}
