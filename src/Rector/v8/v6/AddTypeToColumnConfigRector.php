<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
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

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Array_::class];
    }

    /**
     * toDo: this should go into a base class
     *
     * @param Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof  Array_) {
            return null;
        }

        // check for a tca definition of a full table (with columns and ctrl section)
        $columns = $this->extractSubArrayByKey($node, 'columns');
        $ctrl = $this->extractArrayItemByKey($node, 'ctrl');

        if (null !== $columns && null !== $ctrl) {
            // we found a tca definition of a full table. Process it as a whole:
            return $this->refactorColumnList($columns) ? $node : null;
        }

        // this is not a full tca definition. Lets check some fuzzier stuff.
        // it could be a list of columns, as in ExtensionManagementUtility::addTcaColums('table', $node)
        $hasAstBeenChanged = false;
        foreach ($node->items as $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                // lets play it safe here. a non-associative array is probably not tca.
                continue;
            }

            if (! $arrayItem->key instanceof String_) {
                // the key of a column list in tca is the column name, which needs to be a string.
                continue;
            }

            if ($arrayItem->value instanceof Array_) {
                // we found a single column configuration which is an array
                // (not a call to stuff like ExtensionManagementUtility::getFileFieldTCAConfig)
                $labelNode = $this->extractArrayItemByKey($arrayItem->value, 'label');
                // toDo: not everything that has a label is tca. check for more stuff like config or exclude here!
                //  but in this special case we can't test for 'type' as this rector should add that.
                if (null !== $labelNode) {
                    $hasAstBeenChanged = $this->refactorColumn($arrayItem->key, $arrayItem->value) ? true : $hasAstBeenChanged;
                }
            }
        }

        return $hasAstBeenChanged ? $node : null;
    }

    /**
     * refactors an TCA array such as
     * [
     *      'column_1' => [
     *          'label' => 'column 1', ...
     *          'config' => ...
     *      ],
     *      'column_2' => [
     *          'label' => 'column 2', ...
     *          'config' => ...
     *      ]
     * ]
     *
     * @param Array_ $columns a list of TCA definitions for columns
     * @return bool whether the AST has been changed by the operation
     */
    private function refactorColumnList(Array_ $columns): bool
    {
        $hasAstBeenChanged = false;
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

    /**
     * Refactors a single TCA column definition like
     *
     * 'column_name' => [
     *      'label' => 'column label',
     *      'config' => [],
     * ]
     *
     * @param Expr $columnName the key in above example (typically String_('column_name'))
     * @param Expr $columnTca the value in above example (typically an associative Array with stuff like 'label', 'config', 'exclude', ...)
     * @return bool
     */
    private function refactorColumn(Expr $columnName, Expr $columnTca): bool
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
}
