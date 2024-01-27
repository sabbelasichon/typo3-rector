<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeResolver;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;

final class Typo3NodeResolver
{
    /**
     * @var string
     */
    public const TYPO_SCRIPT_FRONTEND_CONTROLLER = 'TSFE';

    /**
     * @var string
     */
    public const TIME_TRACKER = 'TT';

    /**
     * @var string
     */
    public const PARSETIME_START = 'PARSETIME_START';

    /**
     * @var string
     */
    public const TYPO3_LOADED_EXT = 'TYPO3_LOADED_EXT';

    /**
     * @var string
     */
    public const TYPO3_DB = 'TYPO3_DB';

    /**
     * @var string
     */
    public const BACKEND_USER = 'BE_USER';

    /**
     * @var string
     */
    public const GLOBALS = 'GLOBALS';

    /**
     * @var string
     */
    public const LANG = 'LANG';

    /**
     * @var string
     */
    public const EXEC_TIME = 'EXEC_TIME';

    /**
     * @var string
     */
    public const SIM_EXEC_TIME = 'SIM_EXEC_TIME';

    /**
     * @var string
     */
    public const ACCESS_TIME = 'ACCESS_TIME';

    /**
     * @var string
     */
    public const SIM_ACCESS_TIME = 'SIM_ACCESS_TIME';

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;

    public function __construct(
        ValueResolver $valueResolver,
        NodeNameResolver $nodeNameResolver
    ) {
        $this->valueResolver = $valueResolver;
        $this->nodeNameResolver = $nodeNameResolver;
    }

    public function isMethodCallOnGlobals(Node $node, string $methodCall, string $global): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        if (! $node->var instanceof ArrayDimFetch) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($node->name, $methodCall)) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($node->var->var, self::GLOBALS)) {
            return false;
        }

        if (! $node->var->dim instanceof Expr) {
            return false;
        }

        return $this->valueResolver->isValue($node->var->dim, $global);
    }

    public function isAnyMethodCallOnGlobals(Node $node, string $global): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        if (! $node->var instanceof ArrayDimFetch) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($node->var->var, self::GLOBALS)) {
            return false;
        }

        if (! $node->var->dim instanceof Expr) {
            return false;
        }

        return $this->valueResolver->isValue($node->var->dim, $global);
    }

    public function isTypo3Global(Node $node, string $global): bool
    {
        if (! $node instanceof ArrayDimFetch) {
            return false;
        }

        if ($node->var instanceof MethodCall) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($node->var, self::GLOBALS)) {
            return false;
        }

        if (! $node->dim instanceof Expr) {
            return false;
        }

        return $this->valueResolver->isValue($node->dim, $global);
    }

    /**
     * @param string[] $globals
     */
    public function isTypo3Globals(Node $node, array $globals): bool
    {
        foreach ($globals as $global) {
            if ($this->isTypo3Global($node, $global)) {
                return true;
            }
        }

        return false;
    }

    public function isPropertyFetchOnAnyPropertyOfGlobals(Node $node, string $global): bool
    {
        if (! $node instanceof PropertyFetch) {
            return false;
        }

        if (! $node->var instanceof ArrayDimFetch) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($node->var->var, self::GLOBALS)) {
            return false;
        }

        if (! $node->var->dim instanceof Expr) {
            return false;
        }

        return $this->valueResolver->isValue($node->var->dim, $global);
    }

    public function isMethodCallOnSysPageOfTSFE(Node $node): bool
    {
        return $this->isMethodCallOnPropertyOfGlobals($node, self::TYPO_SCRIPT_FRONTEND_CONTROLLER, 'sys_page');
    }

    public function isMethodCallOnPropertyOfGlobals(Node $node, string $global, string $property): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        if (! $node->var instanceof PropertyFetch) {
            return false;
        }

        if (! $node->var->var instanceof ArrayDimFetch) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($node->var->var->var, self::GLOBALS)) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($node->var->name, $property)) {
            return false;
        }

        if (! $node->var->var->dim instanceof Expr) {
            return false;
        }

        return $this->valueResolver->isValue($node->var->var->dim, $global);
    }

    public function isMethodCallOnBackendUser(Node $node): bool
    {
        return $this->isAnyMethodCallOnGlobals($node, self::BACKEND_USER);
    }
}
