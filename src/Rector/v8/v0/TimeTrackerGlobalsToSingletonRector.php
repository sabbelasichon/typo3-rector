<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Breaking-73504-MakeTimeTrackerASingleton.html
 */
final class TimeTrackerGlobalsToSingletonRector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @return string[]
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
        if (! $this->typo3NodeResolver->isAnyMethodCallOnGlobals($node, Typo3NodeResolver::TIME_TRACKER)) {
            return null;
        }
        $classConstant = $this->createClassConstantReference(TimeTracker::class);
        $staticCall = $this->createStaticCall(GeneralUtility::class, 'makeInstance', [$classConstant]);
        $methodCallName = $this->getName($node->name);
        if (null === $methodCallName) {
            return null;
        }
        return $this->createMethodCall($staticCall, $methodCallName, $node->args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Substitute $GLOBALS[\'TT\'] method calls', [new CodeSample(<<<'PHP'
$GLOBALS['TT']->setTSlogMessage('content');
PHP
, <<<'PHP'
GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('content');
PHP
)]);
    }
}
