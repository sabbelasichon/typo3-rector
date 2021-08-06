<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Tca;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Console\Output\RectorOutputStyle;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;

/**
 * Base rector that detects Arrays containing TCA definitions and allows to refactor them
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

    protected bool $hasAstBeenChanged = false;

    public function __construct(
        protected RectorOutputStyle $rectorOutputStyle
    ) {
    }

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
        $this->resetInnerState();
        $this->hasAstBeenChanged = false;
        if ($this->isFullTcaDefinition($node)) {
            // we found a tca definition of a full table. Process it as a whole:
            $columns = $this->extractSubArrayByKey($node, 'columns');
            if (null !== $columns) {
                $this->refactorColumnList($columns);
            }

            $types = $this->extractSubArrayByKey($node, 'types');
            if (null !== $types) {
                $this->refactorTypes($types);
            }

            $ctrl = $this->extractSubArrayByKey($node, 'ctrl');
            if (null !== $ctrl) {
                $this->refactorCtrl($ctrl);
            }

            return $this->hasAstBeenChanged ? $node : null;
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
            if (! $columnName instanceof Expr) {
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
        if (! $labelNode instanceof ArrayItem) {
            return false;
        }

        $configNode = $this->extractArrayItemByKey($arrayItem->value, self::CONFIG);
        if (! $configNode instanceof ArrayItem) {
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
    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        // override this as needed in child-classes
    }

    /**
     * refactors an TCA types array such as [ '0' => [ 'showitem' => 'field_a,field_b' ], '1' => [ 'showitem' =>
     * 'field_a'] ]
     */
    protected function refactorTypes(Array_ $types): void
    {
        foreach ($types->items as $typeItem) {
            if (! $typeItem instanceof ArrayItem) {
                continue;
            }

            $typeKey = $typeItem->key;
            if (! $typeKey instanceof Expr) {
                continue;
            }

            $typeConfig = $typeItem->value;
            $this->refactorType($typeKey, $typeConfig);
        }
    }

    /**
     * refactors a single TCA type item with key `typeKey` such as [ 'showitem' => 'field_a,field_b' ], '1' => [
     * 'showitem' => 'field_a']
     */
    protected function refactorType(Expr $typeKey, Expr $typeConfig): void
    {
        // override this as needed in child-classes
    }

    /**
     * refactors an TCA ctrl section such as ['label' => 'foo', 'tstamp' => 'tstamp', 'crdate' => 'crdate']
     */
    protected function refactorCtrl(Array_ $ctrl): void
    {
        // override this as needed in child-classes
    }

    /**
     * @param Array_ $array An array into which a new ArrayItem should be inserted
     * @param ArrayItem $newItem The item to be inserted
     * @param string $key The key after which the ArrayItem should be inserted
     */
    protected function insertItemAfterKey(Array_ $array, ArrayItem $newItem, string $key): void
    {
        $positionOfTypeInConfig = 0;
        foreach ($array->items as $configNode) {
            if (! $configNode instanceof ArrayItem) {
                break;
            }
            if (null === $configNode->key || $this->valueResolver->getValue($configNode->key) === $key) {
                break;
            }
            ++$positionOfTypeInConfig;
        }
        array_splice($array->items, $positionOfTypeInConfig + 1, 0, [$newItem]);
    }

    /**
     * may be overridden by child classes to be notified of the start of a node
     */
    protected function resetInnerState(): void
    {
    }
}
