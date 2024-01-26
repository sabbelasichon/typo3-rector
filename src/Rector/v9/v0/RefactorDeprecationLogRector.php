<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeTraverser;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.0/Deprecation-82438-DeprecationMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\RefactorDeprecationLogRector\RefactorDeprecationLogRectorTest
 */
final class RefactorDeprecationLogRector extends AbstractRector
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
     * @return int|null|Node
     */
    public function refactor(Node $node)
    {
        $staticCall = $node->expr;

        if (! $staticCall instanceof StaticCall) {
            return null;
        }

        $className = $this->getName($staticCall->class);
        if ($className !== 'TYPO3\CMS\Core\Utility\GeneralUtility') {
            return null;
        }

        $constFetch = new ConstFetch(new Name('E_USER_DEPRECATED'));

        $usefulMessage = new String_('A useful message');
        $emptyFallbackString = new String_('');
        $arguments = $staticCall->args;

        if ($this->isNames($staticCall->name, ['logDeprecatedFunction', 'logDeprecatedViewHelperAttribute'])) {
            $node->expr = $this->nodeFactory->createFuncCall('trigger_error', [$usefulMessage, $constFetch]);

            return $node;
        }

        if ($this->isName($staticCall->name, 'deprecationLog')) {
            $node->expr = $this->nodeFactory->createFuncCall(
                'trigger_error',
                [$arguments[0] ?? $emptyFallbackString, $constFetch]
            );

            return $node;
        }

        if ($this->isName($staticCall->name, 'getDeprecationLogFileName')) {
            return NodeTraverser::REMOVE_NODE;
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor GeneralUtility deprecationLog methods', [new CodeSample(
            <<<'CODE_SAMPLE'
GeneralUtility::logDeprecatedFunction();
GeneralUtility::logDeprecatedViewHelperAttribute();
GeneralUtility::deprecationLog('Message');
GeneralUtility::getDeprecationLogFileName();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
trigger_error('A useful message', E_USER_DEPRECATED);
CODE_SAMPLE
        )]);
    }
}
