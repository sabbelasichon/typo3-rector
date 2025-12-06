<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107856-DataHandlerRemoveInternalPropertyCopyWhichTablesandPropertiesNeverHideAtCopyandCopyTree.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateCopyRelatedDataHandlerPropertiesRector\MigrateCopyRelatedDataHandlerPropertiesRectorTest
 */
final class MigrateCopyRelatedDataHandlerPropertiesRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate copy related DataHandler properties', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\DataHandling\DataHandler;

class MyClass
{
    public function myMethod(DataHandler $dataHandler)
    {
        $neverHideAtCopy = $dataHandler->neverHideAtCopy;
        $copyTree = $dataHandler->copyTree;
    }
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\DataHandling\DataHandler;

class MyClass
{
    public function myMethod(DataHandler $dataHandler)
    {
        $neverHideAtCopy = $dataHandler->BE_USER->uc['neverHideAtCopy'];
        $copyTree = $dataHandler->BE_USER->uc['copyLevels'];
    }
}
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node->var, new ObjectType('TYPO3\CMS\Core\DataHandling\DataHandler'))) {
            return null;
        }

        if ($this->isName($node->name, 'neverHideAtCopy')) {
            return $this->createBeUserUcFetch($node->var, 'neverHideAtCopy');
        }

        if ($this->isName($node->name, 'copyTree')) {
            return $this->createBeUserUcFetch($node->var, 'copyLevels');
        }

        return null;
    }

    private function createBeUserUcFetch(Expr $variable, string $ucKey): ArrayDimFetch
    {
        $beUserFetch = $this->nodeFactory->createPropertyFetch($variable, 'BE_USER');
        $ucFetch = $this->nodeFactory->createPropertyFetch($beUserFetch, 'uc');

        return new ArrayDimFetch($ucFetch, new String_($ucKey));
    }
}
