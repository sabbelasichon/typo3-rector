<?php

namespace Ssch\TYPO3Rector\Rector\Tca;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;

/**
 * @changelog
 */
abstract class AbstractTcaRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @var string
     */
    protected const CONFIG = 'config';

    /**
     * @var string
     */
    protected const LABEL = 'label';

    /**
     * @var bool
     */
    protected $hasAstBeenChanged = false;

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Array_::class];
    }

    /**
     * @param Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $this->hasAstBeenChanged = false;
        if ($this->isFullTcaDefinition($node)) {
            $columns = $this->extractSubArrayByKey($node, 'columns');
            if (null !== $columns) {
                // we found a tca definition of a full table. Process it as a whole:
                $this->refactorColumnList($columns);
                return $this->hasAstBeenChanged ? $node : null;
            }
        }

        // this is not a full tca definition. Lets check some fuzzier stuff.
        // it could be a list of columns, as in ExtensionManagementUtility::addTcaColums('table', $node)
        foreach ($node->items as $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                // lets play it safe here. a non-associative array is probably not tca.
                continue;
            }

            if (! $arrayItem->key instanceof String_) {
                // the key of a column list in tca is the column name, which needs to be a string.
                continue;
            }

            // we found a single column configuration which is an array
            // (not a call to stuff like ExtensionManagementUtility::getFileFieldTCAConfig)
            if ($arrayItem->value instanceof Array_ && $this->isSingleTcaColumn($arrayItem)) {
                $this->refactorColumn($arrayItem->key, $arrayItem->value);
            }
        }

        return $this->hasAstBeenChanged ? $node : null;
    }

    /**
     * refactors an TCA array such as [ 'column_1' => [ 'label' => 'column 1', 'config' => ... ], 'column_2' => [
     * 'label' => 'column 2', 'config' => ... ] ]
     *
     * @param Array_ $columns a list of TCA definitions for columns
     */
    protected function refactorColumnList(Array_ $columns): void
    {
        foreach ($columns->items as $columnArrayItem) {
            if (! $columnArrayItem instanceof ArrayItem) {
                continue;
            }

            $columnName = $columnArrayItem->key;
            if (null === $columnName) {
                continue;
            }

            $columnTca = $columnArrayItem->value;

            $this->refactorColumn($columnName, $columnTca);
        }
    }

    /**
     * @return bool whether or not the given Array_ is a full TCA definition for a Table
     */
    protected function isFullTcaDefinition(Array_ $possibleTcaArray): bool
    {
        $columns = $this->extractSubArrayByKey($possibleTcaArray, 'columns');
        $ctrl = $this->extractArrayItemByKey($possibleTcaArray, 'ctrl');

        return null !== $columns && null !== $ctrl;
    }

    /**
     * @return bool whether the given array item is the TCA definition of a single column
     */
    protected function isSingleTcaColumn(ArrayItem $arrayItem): bool
    {
        $labelNode = $this->extractArrayItemByKey($arrayItem->value, self::LABEL);
        if (null === $labelNode) {
            return false;
        }

        $configNode = $this->extractArrayItemByKey($arrayItem->value, self::CONFIG);
        if (null === $configNode) {
            return false;
        }

        $typeNode = $this->extractArrayItemByKey($configNode->value, self::TYPE);
        return null !== $typeNode;
    }

    /**
     * Refactors a single TCA column definition like 'column_name' => [ 'label' => 'column label', 'config' => [], ]
     *
     * remark: checking if the passed nodes really are a TCA snippet must be checked by the caller.
     *
     * @param Expr $columnName the key in above example (typically String_('column_name'))
     * @param Expr $columnTca the value in above example (typically an associative Array with stuff like 'label', 'config', 'exclude', ...)
     */
    abstract protected function refactorColumn(Expr $columnName, Expr $columnTca): void;

    /**
     * @param Array_ $array An array into which a new ArrayItem should be inserted
     * @param ArrayItem $newItem The item to be inserted
     * @param string $key The key after which the ArrayItem should be inserted
     */
    protected function insertItemAfterKey(Array_ $array, ArrayItem $newItem, string $key): void
    {
        $positionOfTypeInConfig = 0;
        foreach ($array->items as $configNode) {
            if (null === $configNode) {
                break;
            }
            if (null === $configNode->key || $this->valueResolver->getValue($configNode->key) === $key) {
                break;
            }
            $positionOfTypeInConfig++;
        }
        array_splice($array->items, $positionOfTypeInConfig + 1, 0, [$newItem]);
    }
}
