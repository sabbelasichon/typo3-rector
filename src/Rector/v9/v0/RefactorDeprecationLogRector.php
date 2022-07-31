<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-82438-DeprecationMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\RefactorDeprecationLogRector\RefactorDeprecationLogRectorTest
 */
final class RefactorDeprecationLogRector extends AbstractRector
{
    /**
     * List of nodes this class checks, classes that implements \PhpParser\Node See beautiful map of all nodes
     * https://github.com/rectorphp/rector/blob/master/docs/NodesOverview.md.
     *
     * @return string[]
     */

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $className = $this->getName($node->class);
        if ('TYPO3\CMS\Core\Utility\GeneralUtility' !== $className) {
            return null;
        }

        $constFetch = new ConstFetch(new Name('E_USER_DEPRECATED'));

        $usefulMessage = new String_('A useful message');
        $emptyFallbackString = new String_('');
        $arguments = $node->args;

        if ($this->isNames($node->name, ['logDeprecatedFunction', 'logDeprecatedViewHelperAttribute'])) {
            return $this->nodeFactory->createFuncCall('trigger_error', [$usefulMessage, $constFetch]);
        }

        if ($this->isName($node->name, 'deprecationLog')) {
            return $this->nodeFactory->createFuncCall(
                'trigger_error',
                [$arguments[0] ?? $emptyFallbackString, $constFetch]
            );
        }

        if ($this->isName($node->name, 'getDeprecationLogFileName')) {
            $this->removeNode($node);
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
