<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use PhpParser\Node;
use Rector\Rector\AbstractRector\NameResolverTrait;
use Rector\Rector\AbstractRector\ValueResolverTrait;

final class Typo3NodeResolver
{
    use NameResolverTrait;
    use ValueResolverTrait;

    public const TypoScriptFrontendController = 'TSFE';
    public const TimeTracker = 'TT';

    public const TYPO3_LOADED_EXT = 'TYPO3_LOADED_EXT';

    public function isMethodCallOnGlobals(Node $node, string $methodCall, string $global): bool
    {
        return $node instanceof Node\Stmt\Expression &&
               $node->expr instanceof Node\Expr\MethodCall &&
               $node->expr->var instanceof Node\Expr\ArrayDimFetch &&
               $this->isName($node->expr, $methodCall) &&
               $this->isName($node->expr->var->var, 'GLOBALS') &&
               $this->isValue($node->expr->var->dim, $global);
    }

    public function isAnyMethodCallOnGlobals(Node $node, string $global): bool
    {
        return $node instanceof Node\Stmt\Expression &&
               $node->expr instanceof Node\Expr\MethodCall &&
               $node->expr->var instanceof Node\Expr\ArrayDimFetch &&
               $this->isName($node->expr->var->var, 'GLOBALS') &&
               $this->isValue($node->expr->var->dim, $global);
    }

    public function isTypo3Global(Node $node, string $global): bool
    {
        return $node instanceof  Node\Expr\ArrayDimFetch &&
               $this->isName($node->var, 'GLOBALS') &&
               $this->isValue($node->dim, $global);
    }
}
