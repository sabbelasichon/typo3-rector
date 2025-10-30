<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Important-107848-DataHandlerPropertiesUseridAndAdminRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateDataHandlerPropertiesUserIdAndAdminRector\MigrateDataHandlerPropertiesUserIdAndAdminRectorTest
 */
final class MigrateDataHandlerPropertiesUserIdAndAdminRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate DataHandler properties userid and admin', [new CodeSample(
            <<<'CODE_SAMPLE'
$userId = $dataHandler->userid;

if ($dataHandler->admin) {
    // do something
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$userId = $dataHandler->BE_USER->getUserId();

if ($dataHandler->BE_USER->isAdmin()) {
    // do something
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

        $propertyName = $this->getName($node->name);

        $beUserPropertyFetch = $this->nodeFactory->createPropertyFetch($node->var, 'BE_USER');

        if ($propertyName === 'userid') {
            // Create $dataHandler->BE_USER->getUserId()
            return $this->nodeFactory->createMethodCall($beUserPropertyFetch, 'getUserId');
        }

        if ($propertyName === 'admin') {
            // Create $dataHandler->BE_USER->isAdmin()
            return $this->nodeFactory->createMethodCall($beUserPropertyFetch, 'isAdmin');
        }

        return null;
    }
}
