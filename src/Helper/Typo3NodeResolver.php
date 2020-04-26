<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use Rector\Core\Rector\AbstractRector\NameResolverTrait;
use Rector\Core\Rector\AbstractRector\ValueResolverTrait;

final class Typo3NodeResolver
{
    use NameResolverTrait;
    use ValueResolverTrait;

    public const TypoScriptFrontendController = 'TSFE';
    public const TimeTracker = 'TT';
    public const ParsetimeStart = 'PARSETIME_START';

    public const TYPO3_LOADED_EXT = 'TYPO3_LOADED_EXT';

    public function isMethodCallOnGlobals(Node $node, string $methodCall, string $global): bool
    {
        return $node instanceof MethodCall &&
               $node->var instanceof ArrayDimFetch &&
               $this->isName($node->name, $methodCall) &&
               $this->isName($node->var->var, 'GLOBALS') &&
               $this->isValue($node->var->dim, $global);
    }

    public function isAnyMethodCallOnGlobals(Node $node, string $global): bool
    {
        return $node instanceof MethodCall &&
               $node->var instanceof ArrayDimFetch &&
               $this->isName($node->var->var, 'GLOBALS') &&
               $this->isValue($node->var->dim, $global);
    }

    public function isTypo3Global(Node $node, string $global): bool
    {
        return $node instanceof ArrayDimFetch &&
               $this->isName($node->var, 'GLOBALS') &&
               $this->isValue($node->dim, $global);
    }

    public function isMethodCallOnPropertyOfGlobals(Node $node, string $global, string $property): bool
    {
        return $node instanceof MethodCall &&
               $node->var instanceof PropertyFetch &&
               $node->var->var instanceof ArrayDimFetch &&
               $this->isName($node->var->var->var, 'GLOBALS') &&
               $this->isName($node->var->name, $property) &&
               $this->isValue($node->var->var->dim, $global);
    }
}
