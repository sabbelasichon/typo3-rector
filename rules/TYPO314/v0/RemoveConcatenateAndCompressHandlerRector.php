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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-108055-RemovedPageRendererRelatedHooksAndMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveConcatenateAndCompressHandlerRector\RemoveConcatenateAndCompressHandlerRectorTest
 */
final class RemoveConcatenateAndCompressHandlerRector extends AbstractRector implements DocumentedRuleInterface
{
    private const HANDLERS = [
        'cssConcatenateHandler',
        'cssCompressHandler',
        'jsConcatenateHandler',
        'jsCompressHandler',
    ];

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
        return new RuleDefinition('Remove concatenate and compress handler configuration', [new CodeSample(
            <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['FE']['cssConcatenateHandler'] = \TYPO3\CMS\Core\Resource\ResourceCompressor::class;
$GLOBALS['TYPO3_CONF_VARS']['FE']['cssCompressHandler'] = \TYPO3\CMS\Core\Resource\ResourceCompressor::class;
$GLOBALS['TYPO3_CONF_VARS']['FE']['jsConcatenateHandler'] = \TYPO3\CMS\Core\Resource\ResourceCompressor::class;
$GLOBALS['TYPO3_CONF_VARS']['FE']['jsCompressHandler'] = \TYPO3\CMS\Core\Resource\ResourceCompressor::class;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
-
CODE_SAMPLE
        )]);
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

        if ($path !== null && $this->isHandlerPath($path)) {
            return NodeVisitor::REMOVE_NODE;
        }

        return null;
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
    private function isHandlerPath(array $path): bool
    {
        if (\count($path) !== 4) {
            return false;
        }

        $isTypo3ConfVars = $path[0] === 'GLOBALS' && $path[1] === 'TYPO3_CONF_VARS';
        $isFrontend = $path[2] === 'FE';
        $isHandler = \in_array($path[3], self::HANDLERS, true);

        return $isTypo3ConfVars && $isFrontend && $isHandler;
    }
}
