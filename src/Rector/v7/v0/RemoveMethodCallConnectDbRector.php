<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/7.0/Breaking-61863-ConnectDbFunctionRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v7\v0\RemoveMethodCallConnectDbRector\RemoveMethodCallConnectDbRectorTest
 */
final class RemoveMethodCallConnectDbRector extends AbstractRector
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
        $staticCall = $node->expr;
        if (! $staticCall instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Frontend\Utility\EidUtility')
        )) {
            return null;
        }

        if (! $this->isName($staticCall->name, 'connectDB')) {
            return null;
        }

        return NodeTraverser::REMOVE_NODE;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove EidUtility::connectDB() call', [
            new CodeSample(
                <<<'CODE_SAMPLE'
EidUtility::connectDB()
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
-
CODE_SAMPLE
            ),
        ]);
    }
}
