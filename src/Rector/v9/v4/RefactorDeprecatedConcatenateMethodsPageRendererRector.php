<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.4/Deprecation-65578-ConfigconcatenateJsAndCssAndConcatenateFiles.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\RefactorDeprecatedConcatenateMethodsPageRendererRector\RefactorDeprecatedConcatenateMethodsPageRendererRectorTest
 */
final class RefactorDeprecatedConcatenateMethodsPageRendererRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, Expression::class];
    }

    /**
     * @param MethodCall|Expression $node
     * @return Node[]|null|Node
     */
    public function refactor(Node $node)
    {
        if ($node instanceof MethodCall) {
            if ($this->shouldSkip($node)) {
                return null;
            }

            if ($this->isName($node->name, 'getConcatenateFiles')) {
                return $this->createArrayMergeCall($node);
            }

            return null;
        }

        $methodCall = $node->expr;

        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if ($this->isName($methodCall->name, 'enableConcatenateFiles')) {
            return $this->splitMethodCall($methodCall, 'enableConcatenateJavascript', 'enableConcatenateCss');
        }

        if ($this->isName($methodCall->name, 'disableConcatenateFiles')) {
            return $this->splitMethodCall($methodCall, 'disableConcatenateJavascript', 'disableConcatenateCss');
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Turns method call names to new ones.', [new CodeSample(
            <<<'CODE_SAMPLE'
$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$files = $someObject->getConcatenateFiles();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$files = array_merge($this->getConcatenateCss(), $this->getConcatenateJavascript());
CODE_SAMPLE
        )]);
    }

    private function createArrayMergeCall(MethodCall $methodCall): FuncCall
    {
        $node1 = clone $methodCall;
        $node2 = clone $methodCall;
        $node1->name = new Identifier('getConcatenateCss');
        $node2->name = new Identifier('getConcatenateJavascript');
        return $this->nodeFactory->createFuncCall('array_merge', [new Arg($node1), new Arg($node2)]);
    }

    /**
     * @return Node[]
     */
    private function splitMethodCall(MethodCall $methodCall, string $firstMethod, string $secondMethod): array
    {
        $methodCall->name = new Identifier($firstMethod);

        $node1 = clone $methodCall;
        $node1->name = new Identifier($secondMethod);

        return [new Expression($node1), new Expression($methodCall)];
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Page\PageRenderer')
        )) {
            return true;
        }

        return ! $this->isNames(
            $node->name,
            ['getConcatenateFiles', 'enableConcatenateFiles', 'disableConcatenateFiles']
        );
    }
}
