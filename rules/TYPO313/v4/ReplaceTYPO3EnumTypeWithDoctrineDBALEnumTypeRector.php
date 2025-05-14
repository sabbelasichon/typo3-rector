<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v4;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.4/Deprecation-105279-ReplaceTYPO3EnumTypeWithDoctrinedbalEnumType.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v4\ReplaceTYPO3EnumTypeWithDoctrineDBALEnumTypeRector\ReplaceTYPO3EnumTypeWithDoctrineDBALEnumTypeRectorTest
 */
final class ReplaceTYPO3EnumTypeWithDoctrineDBALEnumTypeRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace TYPO3 EnumType with Doctrine DBAL EnumType', [new CodeSample(
            <<<'CODE_SAMPLE'
$doctrineType = \TYPO3\CMS\Core\Database\Schema\Types\EnumType::TYPE;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$doctrineType = \Doctrine\DBAL\Types\Type::TYPE;
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [];
    }

    /**
     * The actual logic is a configured rule in config/v13/typo3-134.php
     */
    public function refactor(Node $node): ?Node
    {
        return null;
    }
}
