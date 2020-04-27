<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Page;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-65578-ConfigconcatenateJsAndCssAndConcatenateFiles.html
 */
final class RefactorDeprecatedConcatenateMethodsPageRendererRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, PageRenderer::class)) {
            return null;
        }

        if (! $this->isNames(
            $node->name,
            ['getConcatenateFiles', 'enableConcatenateFiles', 'disableConcatenateFiles']
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
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Turns method call names to new ones.', [
            new CodeSample(
                <<<'PHP'
$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$files = $someObject->getConcatenateFiles();
PHP
                ,
                <<<'PHP'
$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$files = array_merge($this->getConcatenateCss(), $this->getConcatenateJavascript());
PHP
            ),
        ]);
    }

    private function createArrayMergeCall(MethodCall $node): FuncCall
    {
        $node1 = clone $node;
        $node2 = clone $node;

        $node1->name = new Identifier('getConcatenateCss');
        $node2->name = new Identifier('getConcatenateJavascript');

        return $this->createFuncCall('array_merge', [new Arg($node1), new Arg($node2)]);
    }

    private function splitMethodCall(MethodCall $node, string $firstMethod, string $secondMethod): Node
    {
        $node->name = new Identifier($firstMethod);
        $node1 = clone $node;
        $node1->name = new Identifier($secondMethod);
        $this->addNodeAfterNode($node1, $node);

        return $node;
    }
}
