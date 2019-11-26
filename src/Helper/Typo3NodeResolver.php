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

    public const TSFE = 'TSFE';

    public function isMethodCallOnGlobals(Node $node, string $methodCall, string $global): bool
    {
        return $node instanceof Node\Stmt\Expression &&
               $node->expr instanceof Node\Expr\MethodCall &&
               $node->expr->var instanceof Node\Expr\ArrayDimFetch &&
               $this->isName($node->expr, $methodCall) &&
               $this->isName($node->expr->var->var, 'GLOBALS') &&
               $this->isValue($node->expr->var->dim, $global);
    }
}
