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
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-65578-ConfigconcatenateJsAndCssAndConcatenateFiles.html
 */
final class RefactorDeprecatedConcatenateMethodsPageRendererRector extends AbstractRector
{
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
            new ObjectType(PageRenderer::class)
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
        return new RuleDefinition('Turns method call names to new ones.', [new CodeSample(<<<'CODE_SAMPLE'
$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$files = $someObject->getConcatenateFiles();
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$files = array_merge($this->getConcatenateCss(), $this->getConcatenateJavascript());
CODE_SAMPLE
)]);
    }

    private function createArrayMergeCall(MethodCall $node): FuncCall
    {
        $node1 = clone $node;
        $node2 = clone $node;
        $node1->name = new Identifier('getConcatenateCss');
        $node2->name = new Identifier('getConcatenateJavascript');
        return $this->nodeFactory->createFuncCall('array_merge', [new Arg($node1), new Arg($node2)]);
    }

    private function splitMethodCall(MethodCall $node, string $firstMethod, string $secondMethod): MethodCall
    {
        $node->name = new Identifier($firstMethod);
        $node1 = clone $node;
        $node1->name = new Identifier($secondMethod);
        $this->addNodeAfterNode($node1, $node);
        return $node;
    }
}
