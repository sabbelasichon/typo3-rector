<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\TimeTracker;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
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
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->typo3NodeResolver->isAnyMethodCallOnGlobals($node, Typo3NodeResolver::TimeTracker)) {
            return null;
        }

        return $this->createMethodCall($this->createStaticCall(
            GeneralUtility::class,
            'makeInstance',
            [
                $this->createClassConstant(TimeTracker::class, 'class'),
            ]
        ), $this->getName($node->expr), $node->expr->args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Substitute $GLOBALS[\'TT\'] method calls', [
            new CodeSample(
                <<<'PHP'
$GLOBALS['TT']->setTSlogMessage('content');
PHP
                ,
                <<<'PHP'
GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('content');
PHP
            ),
        ]);
    }
}
