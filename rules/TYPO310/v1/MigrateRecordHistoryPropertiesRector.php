<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.1/Deprecation-89127-CleanupRecordHistoryHandling.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v1\MigrateRecordHistoryPropertiesRector\MigrateRecordHistoryPropertiesRectorTest
 */
final class MigrateRecordHistoryPropertiesRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<string, string>
     */
    private const PROPERTY_TO_METHOD_MAP = [
        'changeLog' => 'getChangeLog',
        'lastHistoryEntry' => 'getLastHistoryEntryNumber',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate protected RecordHistory properties', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\History\RecordHistory;

$recordHistory = new RecordHistory();
$changeLog = $recordHistory->changeLog;
$lastEntry = $recordHistory->lastHistoryEntry;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\History\RecordHistory;

$recordHistory = new RecordHistory();
$changeLog = $recordHistory->getChangeLog();
$lastEntry = $recordHistory->getLastHistoryEntryNumber();
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
        if (! $this->isObjectType($node->var, new ObjectType('TYPO3\CMS\Core\History\RecordHistory'))) {
            return null;
        }

        $propertyName = $this->getName($node->name);
        if ($propertyName === null) {
            return null;
        }

        if (! isset(self::PROPERTY_TO_METHOD_MAP[$propertyName])) {
            return null;
        }

        $methodName = self::PROPERTY_TO_METHOD_MAP[$propertyName];

        return $this->nodeFactory->createMethodCall($node->var, $methodName);
    }
}
