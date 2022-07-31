<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-65578-ConfigconcatenateJsAndCssAndConcatenateFiles.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\RefactorDeprecatedConcatenateMethodsPageRendererRector\RefactorDeprecatedConcatenateMethodsPageRendererRectorTest
 */
final class RefactorDeprecatedConcatenateMethodsPageRendererRector extends AbstractRector
{
    /**
     * @readonly
     */
    public NodesToAddCollector $nodesToAddCollector;

    public function __construct(NodesToAddCollector $nodesToAddCollector)
    {
        $this->nodesToAddCollector = $nodesToAddCollector;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Page\PageRenderer')
        )) {
            return null;
        }

        if ($this->isName($node->name, 'getConcatenateFiles')) {
            return $this->createArrayMergeCall($node);
        }

        if ($this->isName($node->name, 'enableConcatenateFiles')) {
            return $this->splitMethodCall($node, 'enableConcatenateJavascript', 'enableConcatenateCss');
        }

        if ($this->isName($node->name, 'disableConcatenateFiles')) {
            return $this->splitMethodCall($node, 'disableConcatenateJavascript', 'disableConcatenateCss');
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

    private function splitMethodCall(MethodCall $methodCall, string $firstMethod, string $secondMethod): MethodCall
    {
        $methodCall->name = new Identifier($firstMethod);

        $node1 = clone $methodCall;
        $node1->name = new Identifier($secondMethod);
        $this->nodesToAddCollector->addNodeAfterNode($node1, $methodCall);

        return $methodCall;
    }
}
