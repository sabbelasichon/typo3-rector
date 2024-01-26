<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.0/Breaking-72384-RemovedDeprecatedCodeFromHtmlParser.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v0\CoreRector\Html\RemoveRteHtmlParserEvalWriteFileRectorTest
 */
final class RemoveRteHtmlParserEvalWriteFileRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class];
    }

    /**
     * @param Node\Stmt\Expression $node
     */
    public function refactor(Node $node): ?int
    {
        $staticOrMethodCall = $node->expr;

        if (! $staticOrMethodCall instanceof StaticCall && ! $staticOrMethodCall instanceof MethodCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticOrMethodCall,
            new ObjectType('TYPO3\CMS\Core\Html\RteHtmlParser')
        )) {
            return null;
        }

        if ($this->isName($staticOrMethodCall->name, 'evalWriteFile')) {
            $methodName = $this->getName($staticOrMethodCall->name);
            if ($methodName === null) {
                return null;
            }

            return NodeTraverser::REMOVE_NODE;
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('remove evalWriteFile method from RteHtmlparser.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Html\RteHtmlParser;

final class RteHtmlParserRemovedMethods
{
    public function doSomething(): void
    {
        $rtehtmlparser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(RteHtmlParser::class);
        $rtehtmlparser->evalWriteFile();
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Html\RteHtmlParser;

final class RteHtmlParserRemovedMethods
{
    public function doSomething(): void
    {
        $rtehtmlparser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(RteHtmlParser::class);
    }
}
CODE_SAMPLE
            ),
        ]);
    }
}
