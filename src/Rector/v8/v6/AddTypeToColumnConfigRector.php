<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79440-TcaChanges.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\AddTypeToColumnConfigRectorTest
 */
final class AddTypeToColumnConfigRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const TYPE = 'type';

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Return_::class, Node\Expr\StaticCall::class];
    }

    /**
     * toDo: this should go into a base class
     *
     * @param Node|Node\Expr\StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasAstBeenChanged = $this->refactorFullTca($node);
        $hasAstBeenChanged = $this->refactorAddTcaColumns($node) ? true : $hasAstBeenChanged;

        return $hasAstBeenChanged ? $node : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add type to column config if not exists', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'bar' => []
    ]
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'bar' => [
            'config' => [
                'type' => 'none'
            ]
        ]
    ]
];
CODE_SAMPLE
        )]);
    }

    private function refactorColumn(Node\Expr $columnName, Node\Expr $columnTca): bool
    {
        if (! $columnTca instanceof Array_) {
            return false;
        }
        $config = null;
        $configItem = $this->extractArrayItemByKey($columnTca, 'config');

        if (null !== $configItem) {
            $config = $configItem->value;
            if (! $config instanceof Array_) {
                return false;
            }
        }

        $hasAstBeenChanged = false;
        if (null === $config) {
            // found a column without a 'config' part. Create an empty 'config' array
            $config = new Array_();
            $columnTca->items[] = new ArrayItem($config, new String_('config'));
            $hasAstBeenChanged = true;
        }

        if (null === $this->extractArrayItemByKey($config, self::TYPE)) {
            // found a column without a 'type' field in the config. add type => none
            $config->items[] = new ArrayItem(new String_('none'), new String_(self::TYPE));
            $hasAstBeenChanged = true;
        }
        return $hasAstBeenChanged;
    }

    // ToDo: this should go into a base class
    private function refactorFullTca(Node $node): bool
    {
        $hasAstBeenChanged = false;
        if (! $node instanceof Return_ || ! $this->isFullTca($node)) {
            return false;
        }

        $columns = $this->extractSubArrayByKey($node->expr, 'columns');
        if (null === $columns) {
            return false;
        }

        foreach ($columns->items as $columnArrayItem) {
            if (! $columnArrayItem instanceof ArrayItem) {
                continue;
            }

            $columnName = $columnArrayItem->key;
            if (null === $columnName) {
                continue;
            }

            $columnTca = $columnArrayItem->value;

            $hasAstBeenChanged = $this->refactorColumn($columnName, $columnTca) ? true : $hasAstBeenChanged;
        }
        return $hasAstBeenChanged;
    }

    // todo: this should go into a base class
    private function resolveVariableDefinition(Node\Expr\Variable $columnsDefinition): ?Node
    {
        // we need to find the definition of this variable and refactor that.
        // as a first-order approximation, we look at the previous statement and hope that the argument is defined there
        $previousStatement = $columnsDefinition->getAttribute(AttributeKey::PREVIOUS_STATEMENT);
        if (! $previousStatement->expr instanceof Node\Expr\Assign) {
            // the previous statement is not an assignment
            return null;
        }
        $assignment = $previousStatement->expr;

        if (! $assignment->var instanceof Node\Expr\Variable) {
            // it is not assigning to a variable
            return null;
        }

        if ($assignment->var->name !== $columnsDefinition->name) {
            // it is assigning to a different variable
            return null;
        }
        if (! $assignment->expr instanceof Array_) {
            // the assigned value is not an array
            return null;
        }

        // we found the array definition that is used as the calling argument to addTcaColumns.
        return $assignment->expr;
    }

    // todo: this should go into a base class
    private function refactorAddTcaColumns(Node $node): bool
    {
        if (! $node instanceof Node\Expr\StaticCall) {
            return false;
        }

        if (! $this->nodeTypeResolver->isObjectType(
            $node->class,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )) {
            return false;
        }

        if (! $this->isName($node->name, 'addTCAcolumns')) {
            return false;
        }

        if (2 !== count($node->args)) {
            return false;
        }

        [$tableNameArgument, $columnsDefinitionArgument] = $node->args;

        if (! $tableNameArgument->value instanceof String_) {
            // lets play it safe here - dont refactor calls with the table name not being a string
            return false;
        }

        $columnsDefinition = $columnsDefinitionArgument->value;
        if ($columnsDefinition instanceof Node\Expr\Variable) {
            // the call uses a variable to define the columns.
            // For refactoring we need the node where this variable is defined
            $columnsDefinition = $this->resolveVariableDefinition($columnsDefinition);
        }

        if (! $columnsDefinition instanceof Array_) {
            return false;
        }

        $hasAstBeenChanged = false;
        foreach ($columnsDefinition->items as $columnArrayItem) {
            if (! $columnArrayItem instanceof ArrayItem) {
                continue;
            }

            $columnNameNode = $columnArrayItem->key;
            $columnConfiguration = $columnArrayItem->value;

            if (null === $columnNameNode) {
                continue;
            }

            $hasAstBeenChanged = $this->refactorColumn(
                $columnNameNode,
                $columnConfiguration
            ) ? true : $hasAstBeenChanged;
        }
        return $hasAstBeenChanged;
    }
}
