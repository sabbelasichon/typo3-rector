<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v3;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.3/Deprecation-94165-SysLanguageDatabaseTable.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v3\MigrateSpecialLanguagesToTcaTypeLanguageRector\MigrateSpecialLanguagesToTcaTypeLanguageRectorTest
 */
final class MigrateSpecialLanguagesToTcaTypeLanguageRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    private ?string $languageField = null;

    /**
     * @param Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $this->hasAstBeenChanged = false;
        if (! $this->isFullTcaDefinition($node)) {
            return null;
        }

        $ctrlArray = $this->extractSubArrayByKey($node, 'ctrl');
        if (! $ctrlArray instanceof Array_) {
            return null;
        }

        $value = $this->extractArrayValueByKey($ctrlArray, 'languageField');
        if (! $value instanceof String_) {
            return null;
        }

        $this->languageField = $this->valueResolver->getValue($value);
        if ($this->languageField === null) {
            return null;
        }

        // we found a tca definition of a full table. Process it as a whole:
        $columnsArray = $this->extractSubArrayByKey($node, 'columns');
        if ($columnsArray instanceof Array_) {
            $this->refactorColumnList($columnsArray);
        }

        return $this->hasAstBeenChanged ? $node : null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'use the new TCA type language instead of foreign_table => sys_language for selecting a records',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'languageField' => 'sys_language_uid',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'Language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
            ],
        ],
    ],
];
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'languageField' => 'sys_language_uid',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'Language',
            'config' => [
                'type' => 'language',
            ],
        ],
    ],
];
CODE_SAMPLE
                ),
            ]
        );
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $column = $this->valueResolver->getValue($columnName);
        if ($column !== $this->languageField) {
            return;
        }

        $configuration = $this->extractArrayItemByKey($columnTca, self::CONFIG);
        if (! $configuration instanceof ArrayItem) {
            return;
        }

        $type = $this->extractArrayItemByKey($configuration->value, 'type');
        if (! $type instanceof ArrayItem) {
            return;
        }

        if (! $this->valueResolver->isValue($type->value, 'select')) {
            return;
        }

        $special = $this->extractArrayItemByKey($configuration->value, 'special');
        if (! $special instanceof ArrayItem) {
            return;
        }

        if (! $this->valueResolver->isValue($special->value, 'languages')) {
            return;
        }

        $configuration->value = $this->nodeFactory->createArray([
            'type' => 'language',
        ]);

        $this->hasAstBeenChanged = true;
    }
}
