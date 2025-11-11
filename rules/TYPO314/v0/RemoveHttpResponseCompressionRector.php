<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107943-FrontendAndBackendHTTPResponseCompressionRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveHttpResponseCompressionRector\RemoveHttpResponseCompressionRectorTest
 */
final class RemoveHttpResponseCompressionRector extends AbstractRector implements DocumentedRuleInterface
{
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
        return new RuleDefinition(
            'Remove Application HTTP Response Compression configuration. Use Webserver compression instead.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['BE']['compressionLevel'] = 9;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
-
CODE_SAMPLE
                ),

            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?int
    {
        if (! $node->expr instanceof Assign) {
            return null;
        }

        $assign = $node->expr;

        if (! $assign->var instanceof ArrayDimFetch) {
            return null;
        }

        $path = $this->extractConfigPath($assign->var);

        if ($path === null || ! $this->isCompressionLevelPath($path)) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }

    /**
     * @return string[]|null
     */
    private function extractConfigPath(Expr $expr): ?array
    {
        $path = [];
        $currentNode = $expr;

        while ($currentNode instanceof ArrayDimFetch) {
            if (! $currentNode->dim instanceof Expr) {
                return null;
            }

            $dimValue = $this->valueResolver->getValue($currentNode->dim);
            if (! is_string($dimValue) && ! is_int($dimValue)) {
                return null;
            }

            array_unshift($path, (string) $dimValue);
            $currentNode = $currentNode->var;
        }

        if ($currentNode instanceof Variable && $this->isName($currentNode, 'GLOBALS')) {
            array_unshift($path, 'GLOBALS');
            return $path;
        }

        return null;
    }

    /**
     * @param string[] $path
     */
    private function isCompressionLevelPath(array $path): bool
    {
        if (\count($path) !== 4) {
            return false;
        }

        $isTypo3ConfVars = $path[0] === 'GLOBALS' && $path[1] === 'TYPO3_CONF_VARS';
        $isBackend = $path[2] === 'BE';
        $isCompressionLevel = $path[3] === 'compressionLevel';

        return $isTypo3ConfVars && $isBackend && $isCompressionLevel;
    }
}
