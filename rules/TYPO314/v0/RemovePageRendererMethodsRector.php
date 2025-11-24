<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-108055-RemovedPageRendererRelatedHooksAndMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemovePageRendererMethodsRector\RemovePageRendererMethodsRectorTest
 */
final class RemovePageRendererMethodsRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var string[]
     */
    private const METHODS_TO_REMOVE = [
        'disableConcatenateCss',
        'enableConcatenateCss',
        'getConcatenateCss',
        'disableCompressCss',
        'enableCompressCss',
        'getCompressCss',
        'disableConcatenateJavascript',
        'enableConcatenateJavascript',
        'getConcatenateJavascript',
        'disableCompressJavascript',
        'enableCompressJavascript',
        'getCompressJavascript',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove PageRenderer methods', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$pageRenderer->disableConcatenateCss();
$pageRenderer->enableCompressJavascript();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
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
        $methodCall = $node->expr;

        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if (! $this->isObjectType($methodCall->var, new ObjectType('TYPO3\CMS\Core\Page\PageRenderer'))) {
            return null;
        }

        if (! $this->isNames($methodCall->name, self::METHODS_TO_REMOVE)) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
