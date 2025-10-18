<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-106972-TCAControlOptionSearchFieldsRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveTcaControlOptionSearchFieldsRector\RemoveTcaControlOptionSearchFieldsRectorTest
 */
final class RemoveTcaControlOptionSearchFieldsRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    private const SEARCHABLE_TYPES = ['color', 'email', 'flex', 'input', 'json', 'link', 'slug', 'text', 'uuid'];

    /**
     * @var array<int, string>
     */
    private array $searchFields = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove TCA control option searchFields', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'title' => 'foobar',
        'searchFields' => 'title,description',
    ],
    'columns' => [
        'title' => [
            'config' => ['type' => 'text'],
        ],
        'notes' => [
            'config' => ['type' => 'text'],
        ],
        'brand' => [
            'config' => ['type' => 'color'],
        ],
        'file' => [
            'config' => ['type' => 'file'],
        ],
        'date' => [
            'config' => ['type' => 'datetime', 'dbType' => 'date'],
        ],
        'date2' => [
            'config' => ['type' => 'datetime'],
        ],
        'date3' => [
            'config' => ['type' => 'datetime', 'searchable' => false],
        ],
        'date4' => [
            'config' => ['type' => 'datetime', 'searchable' => true],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'title' => 'foobar',
    ],
    'columns' => [
        'title' => [
            'config' => ['type' => 'text'],
        ],
        'notes' => [
            'config' => ['type' => 'text', 'searchable' => false],
        ],
        'brand' => [
            'config' => ['type' => 'color', 'searchable' => false],
        ],
        'file' => [
            'config' => ['type' => 'file'],
        ],
        'date' => [
            'config' => ['type' => 'datetime', 'dbType' => 'date'],
        ],
        'date2' => [
            'config' => ['type' => 'datetime', 'searchable' => false],
        ],
        'date3' => [
            'config' => ['type' => 'datetime', 'searchable' => false],
        ],
        'date4' => [
            'config' => ['type' => 'datetime', 'searchable' => true],
        ],
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorCtrl(Array_ $ctrlArray): void
    {
        $searchFieldsArrayItem = $this->extractArrayItemByKey($ctrlArray, 'searchFields');
        if (! $searchFieldsArrayItem instanceof ArrayItem) {
            return;
        }

        $searchFieldListValue = $this->valueResolver->getValue($searchFieldsArrayItem->value);
        if (! is_string($searchFieldListValue)) {
            return;
        }

        // park the search fields in a property for later processing
        $this->searchFields = ArrayUtility::trimExplode(',', $searchFieldListValue, true);

        $this->removeArrayItemFromArrayByKey($ctrlArray, 'searchFields');
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        if ($this->hasAstBeenChanged === false) {
            return;
        }

        $configArrayItem = $this->extractArrayItemByKey($columnTca, self::CONFIG);
        if (! $configArrayItem instanceof ArrayItem || ! $configArrayItem->value instanceof Array_) {
            return;
        }

        $configArray = $configArrayItem->value;

        $typeArrayItem = $this->extractArrayItemByKey($configArray, 'type');
        if (! $typeArrayItem instanceof ArrayItem) {
            return;
        }

        $type = $this->valueResolver->getValue($typeArrayItem->value);
        if (! is_string($type) || $type === '') {
            return;
        }

        if ($this->extractArrayItemByKey($configArray, 'searchable') instanceof ArrayItem) {
            return;
        }

        $fieldName = $this->valueResolver->getValue($columnName);
        if (! is_string($fieldName)) {
            return;
        }

        if (in_array($fieldName, $this->searchFields, true)) {
            return;
        }

        $isSearchableType = in_array($type, self::SEARCHABLE_TYPES, true);

        $isSpecialDateTime = false;
        if ($type === 'datetime') {
            $dbTypeArrayItem = $this->extractArrayItemByKey($configArray, 'dbType');
            $dbType = null;
            if ($dbTypeArrayItem instanceof ArrayItem) {
                $dbType = $this->valueResolver->getValue($dbTypeArrayItem->value);
            }

            if (! in_array($dbType, ['date', 'datetime', 'time'], true)) {
                $isSpecialDateTime = true;
            }
        }

        // 6. Final condition: if not (A or B), return.
        if (! $isSearchableType && ! $isSpecialDateTime) {
            return;
        }

        $configArray->items[] = new ArrayItem(new ConstFetch(new Name('false')), new String_('searchable'));
        $this->hasAstBeenChanged = true;
    }
}
