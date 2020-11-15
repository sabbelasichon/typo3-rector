<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Renaming\NodeManipulator\ClassRenamer;
use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Deprecation-73185-DeprecateNullTimeTracker.html
 */
final class TimeTrackerInsteadOfNullTimeTrackerRector extends AbstractRector
{
    /**
     * @var ClassRenamer
     */
    private $classRenamer;

    public function __construct(ClassRenamer $classRenamer)
    {
        $this->classRenamer = $classRenamer;
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [
            StaticCall::class,
            MethodCall::class,
            Name::class,
            Property::class,
            FunctionLike::class,
            Expression::class,
            ClassLike::class,
            Namespace_::class,
            FileWithoutNamespace::class,
        ];
    }

    /**
     * @param MethodCall|StaticCall|FunctionLike|Name|ClassLike|Expression|Namespace_|Property|FileWithoutNamespace $node
     */
    public function refactor(Node $node): ?Node
    {
        $changedNode = $this->addAdditionalArgumentIfNeeded($node);
        if (null !== $changedNode) {
            return $changedNode;
        }

        $renamedNode = $this->classRenamer->renameNode($node, [
            NullTimeTracker::class => TimeTracker::class,
        ]);

        if (null === $renamedNode) {
            return null;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        if ($parentNode instanceof New_) {
            $parentNode->args = $this->createArgs([false]);
        }

        return $renamedNode;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use class TimeTracker instead of NullTimeTracker', [
            new CodeSample(<<<'PHP'
use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$timeTracker1 = new NullTimeTracker();
$timeTracker2 = GeneralUtility::makeInstance(NullTimeTracker::class);
PHP
                , <<<'PHP'
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$timeTracker1 = new TimeTracker(false);
$timeTracker2 = GeneralUtility::makeInstance(TimeTracker::class, false);
PHP
            ),
        ]);
    }

    private function addAdditionalArgumentIfNeeded(Node $node): ?Node
    {
        if (! $node instanceof MethodCall && ! $node instanceof StaticCall) {
            return null;
        }

        if (! $this->isMakeInstanceCall($node) && ! $this->isObjectManagerCall($node)) {
            return null;
        }

        if (! $this->isValue($node->args[0]->value, NullTimeTracker::class)) {
            return null;
        }

        $node->args[1] = $this->createArg(false);

        return $node;
    }

    private function isMakeInstanceCall(Node $node): bool
    {
        if (! $node instanceof StaticCall) {
            return false;
        }

        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return false;
        }

        return $this->isName($node->name, 'makeInstance');
    }

    private function isObjectManagerCall(Node $node): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ObjectManager::class)) {
            return false;
        }

        return $this->isName($node->name, 'get');
    }
}
