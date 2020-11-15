<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\Renaming\NodeManipulator\ClassRenamer;
use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Deprecation-73185-DeprecateNullTimeTracker.html
 */
final class DeprecatedNullTimeTrackerRector extends AbstractRector
{
    /**
     * @var ClassRenamer
     */
    private $classRenamer;

    public function __construct(ClassRenamer $classRenamer)
    {
        $this->classRenamer = $classRenamer;
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, NullTimeTracker::class)) {
            return null;
        }

        $this->classRenamer->renameNode($node, [
            NullTimeTracker::class => TimeTracker::class
        ]);

        $secondParameter = $this->createArg(false);
        $classConstant = $this->createClassConstantReference(TimeTracker::class);
        return $this->createStaticCall(GeneralUtility::class, 'makeInstance', [$classConstant, $secondParameter]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Replace NullTimeTracker with TimeTracker', [new CodeSample(
            <<<'PHP'
$someObject = GeneralUtility::makeInstance(NullTimeTracker::class);
PHP
            ,
            <<<'PHP'
$someObject = GeneralUtility::makeInstance(TimeTracker::class, [false]);
PHP
        )]);
    }
}
