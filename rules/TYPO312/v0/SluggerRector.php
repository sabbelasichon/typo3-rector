<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Deprecation-102793-PageRepositoryEnableFields.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\SluggerRector\SluggerRectorTest
 */
final class SluggerRector extends AbstractTcaRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add slash ("/") replacement in slug fields definition',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
return [
    'columns' => [
        'path' => [
            'exclude' => true,
            'label' => 'LLL:EXT:catalog_resources/Resources/Private/Language/Common.xlf:path',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => [
                        'title',
                    ],
                    'prefixParentPageSlug' => false,
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInPid',
            ],
        ],
    ],
];
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
return [
    'columns' => [
        'path' => [
            'exclude' => true,
            'label' => 'LLL:EXT:catalog_resources/Resources/Private/Language/Common.xlf:path',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => [
                        'title',
                    ],
                    'prefixParentPageSlug' => false,
                    'replacements' => ['/' => ''],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInPid',
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
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return;
        }

        // Return early, if not TCA type "slug"
        if (! $this->isConfigType($configArray, 'slug')) {
            return;
        }

        // Check if generator options exist at all, if not, leave as is
        $generatorOptionsItem = $this->extractSubArrayByKey($configArray, 'generatorOptions');
        if (! $generatorOptionsItem instanceof Array_) {
            return;
        }

        // Prepare the rule to add, if needed
        $slashRule = new ArrayItem(new String_(''), new String_('/'));

        // Check if there's a "replacements" section among the generator options
        $replacementItems = $this->extractSubArrayByKey($generatorOptionsItem, 'replacements');

        $astBeenChanged = false;
        // If "replacements" already exists, check if it has the slash rule
        if ($replacementItems instanceof Array_) {
            $hasAlreadySlashRule = false;
            foreach ($replacementItems->items as $replacementItem) {
                if ($replacementItem === null) {
                    continue;
                }

                if ($replacementItem->key === null) {
                    continue;
                }

                $keyValue = $this->valueResolver->getValue($replacementItem->key);

                if ($keyValue === '/') {
                    $hasAlreadySlashRule = true;
                }
            }

            if (! $hasAlreadySlashRule) {
                $replacementItems->items[] = $slashRule;
                $astBeenChanged = true;
            }
            // No "replacements" yet, add the whole section
        } else {
            $replacements = new ArrayItem(new Array_([$slashRule]), new String_('replacements'));
            $generatorOptionsItem->items[] = $replacements;
            $astBeenChanged = true;
        }

        $this->hasAstBeenChanged = $astBeenChanged;
    }
}
