<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107963-SysRedirectDefaultTypeName.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateSysRedirectDefaultTypeRector\MigrateSysRedirectDefaultTypeRectorTest
 */
final class MigrateSysRedirectDefaultTypeRector extends AbstractRector implements DocumentedRuleInterface
{
    private const TCA = 'TCA';

    private const SYS_REDIRECT = 'sys_redirect';

    private const TYPES = 'types';

    private const TYPE_1 = '1';

    private const TYPE_DEFAULT = 'default';

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate sys_redirect default type name to "default"', [
            new CodeSample(
                <<<'CODE_SAMPLE'
// In Configuration/TCA/Overrides/sys_redirect.php
$GLOBALS['TCA']['sys_redirect']['types']['1']['label'] = 'My custom label';
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// In Configuration/TCA/Overrides/sys_redirect.php
$GLOBALS['TCA']['sys_redirect']['types']['default']['label'] = 'My custom label';
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->var instanceof ArrayDimFetch) {
            return null;
        }

        $path = $this->extractTcaPath($node->var);
        if ($path === null) {
            return null;
        }

        if (\count($path) < 5
            || $path[0] !== 'GLOBALS'
            || $path[1] !== self::TCA
            || $path[2] !== self::SYS_REDIRECT
            || $path[3] !== self::TYPES
            || $path[4] !== self::TYPE_1
        ) {
            return null;
        }

        $remainingPath = array_slice($path, 5);
        $node->var = $this->createTcaDefaultDimFetch($remainingPath);

        return $node;
    }

    /**
     * @return string[]|null
     */
    private function extractTcaPath(Expr $expr): ?array
    {
        $path = [];
        $currentNode = $expr;

        while ($currentNode instanceof ArrayDimFetch) {
            if ($currentNode->dim instanceof Expr) {
                $dimValue = $this->valueResolver->getValue($currentNode->dim);
                if (! is_string($dimValue) && ! is_int($dimValue)) {
                    return null;
                }

                array_unshift($path, (string) $dimValue);
            }

            $currentNode = $currentNode->var;
        }

        if ($currentNode instanceof Variable && $this->isName($currentNode, 'GLOBALS')) {
            array_unshift($path, 'GLOBALS');
            return $path;
        }

        return null;
    }

    /**
     * @param string[] $subPath
     */
    private function createTcaDefaultDimFetch(array $subPath): Expr
    {
        $baseExpr = new ArrayDimFetch(
            new ArrayDimFetch(
                new ArrayDimFetch(
                    new ArrayDimFetch(new Variable('GLOBALS'), new String_(self::TCA)),
                    new String_(self::SYS_REDIRECT)
                ),
                new String_(self::TYPES)
            ),
            new String_(self::TYPE_DEFAULT)
        );

        $currentExpr = $baseExpr;
        foreach ($subPath as $pathPart) {
            $currentExpr = new ArrayDimFetch($currentExpr, new String_($pathPart));
        }

        return $currentExpr;
    }
}
