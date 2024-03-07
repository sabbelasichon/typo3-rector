<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102632-UseStrictTypesInExtbase.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\StrictTypesPersistenceManagerRector\StrictTypesPersistenceManagerRectorTest
 */
final class StrictTypesPersistenceManagerRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Strict types for PersistenceManager', [new CodeSample(
            <<<'CODE_SAMPLE'
    protected $newObjects = [];
    protected $changedObjects;
    protected $addedObjects;
    protected $removedObjects;
    protected $queryFactory;
    protected $backend;
    protected $persistenceSession;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
    protected array $newObjects = [];
    protected ObjectStorage $changedObjects;
    protected ObjectStorage $addedObjects;
    protected ObjectStorage $removedObjects;
    protected QueryFactoryInterface $queryFactory;
    protected BackendInterface $backend;
    protected Session $persistenceSession;
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node)
    {
        return null;
    }
}
