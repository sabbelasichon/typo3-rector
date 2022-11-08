<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use Rector\NodeNameResolver\NodeNameResolver;

final class CommandOutputWritelnDecorator
{
    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;

    public function __construct(NodeNameResolver $nodeNameResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
    }

    public function decorate(MethodCall $node): void
    {
        if ($this->shouldSkip($node)) {
            return;
        }

        $node->var = new Variable('output');
        $node->name = new Identifier('writeln');
    }

    private function shouldSkip(MethodCall $node): bool
    {
        return ! $this->nodeNameResolver->isName($node->name, 'outputLine');
    }
}
