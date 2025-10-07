<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107356-UseRecordAPIInListModule.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseRecordApiInListModuleRector\UseRecordApiInListModuleRectorTest
 */
final class UseRecordApiInListModuleRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<string, int>
     */
    private const ROW_ARGUMENT_POSITIONS = [
        'renderListRow' => 1,
        'makeControl' => 1,
        'makeCheckbox' => 1,
        'languageFlag' => 1,
        'makeLocalizationPanel' => 1,
        'getPreviewUriBuilder' => 1,
        'isRowListingConditionFulfilled' => 1,
        'linkWrapItems' => 3,
        'isRecordDeletePlaceholder' => 0,
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use Record API in List Module', [new CodeSample(
            <<<'CODE_SAMPLE'
$this->renderListRow($table, $rowArray, $indent, $translations, $enabled);
$this->makeControl($table, $row);
$this->makeCheckbox($table, $row);
$this->languageFlag($table, $row);
$this->makeLocalizationPanel($table, $row);
$this->linkWrapItems($table, 2, 'code', $row);
$this->getPreviewUriBuilder($table, $row);
$this->isRecordDeletePlaceholder($row);
$this->isRowListingConditionFulfilled($table, $row);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $rowArray);
$this->renderListRow($table, $record, $indent, $translations, $enabled);
$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
$this->makeControl($table, $record);
$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
$this->makeCheckbox($table, $record);
$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
$this->languageFlag($table, $record);
$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
$this->makeLocalizationPanel($table, $record);
$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
$this->linkWrapItems($table, 2, 'code', $record);
$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
$this->getPreviewUriBuilder($table, $record);
$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
$this->isRecordDeletePlaceholder($record);
$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
$this->isRowListingConditionFulfilled($record);
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return Node[]|null
     */
    public function refactor(Node $node): ?array
    {
        $methodCall = $node->expr;
        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if (! $this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3\CMS\Backend\RecordList\DatabaseRecordList')
        )) {
            return null;
        }

        $methodName = $this->getName($methodCall->name);
        if (! is_string($methodName) || ! array_key_exists($methodName, self::ROW_ARGUMENT_POSITIONS)) {
            return null;
        }

        $rowArgumentPosition = self::ROW_ARGUMENT_POSITIONS[$methodName];
        $args = $methodCall->getArgs();

        if (! isset($args[$rowArgumentPosition])) {
            return null;
        }

        $rowArrayArgument = $args[$rowArgumentPosition];
        $rowArrayType = $this->nodeTypeResolver->getType($rowArrayArgument->value);

        if ($rowArrayType->isArray()->no()) {
            return null;
        }

        $tableArgument = $this->getTableArgument($methodName, $args);
        if (! $tableArgument instanceof Arg) {
            $tableArgument = new Arg(new Variable('table'));
        }

        $recordFactoryCall = new MethodCall(
            new PropertyFetch(new Variable('this'), 'recordFactory'),
            'createResolvedRecordFromDatabaseRow',
            [$tableArgument, $rowArrayArgument]
        );

        $recordVariable = new Variable('record');
        $newStatement = new Expression(new Assign($recordVariable, $recordFactoryCall));

        if ($methodName === 'isRowListingConditionFulfilled') {
            // This method drops the $table argument and only expects the record
            $methodCall->args = [new Arg($recordVariable)];
        } else {
            // Other methods just replace the row array with the record
            $methodCall->args[$rowArgumentPosition] = new Arg($recordVariable);
        }

        return [$newStatement, $node];
    }

    /**
     * @param Arg[] $args
     */
    private function getTableArgument(string $methodName, array $args): ?Arg
    {
        if ($methodName === 'isRecordDeletePlaceholder') {
            // This method doesn't have a table argument
            return null;
        }

        // For all other methods, the table is the first argument
        return $args[0] ?? null;
    }
}
