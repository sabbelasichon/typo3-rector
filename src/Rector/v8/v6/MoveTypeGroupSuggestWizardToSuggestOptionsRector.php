<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79440-TcaChanges.html#suggest-wizard
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v6\MoveTypeGroupSuggestWizardToSuggestOptions\MoveTypeGroupSuggestWizardToSuggestOptionsTest
 */
final class MoveTypeGroupSuggestWizardToSuggestOptionsRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const TYPE = 'type';

    /**
     * @var bool
     */
    private $hasAstBeenChanged = false;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate the "suggest" wizard in type=group to "hideSuggest" and "suggestOptions"', [
            new CodeSample(
                <<<'CODE_SAMPLE'
[
    'columns' => [
        'group_db_8' => [
            'label' => 'group_db_8',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
                'wizards' => [
                    '_POSITION' => 'top',
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'pidList' => 42,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
[
    'columns' => [
        'group_db_8' => [
            'label' => 'group_db_8',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
                'suggestOptions' => [
                    'default' => [
                        'pidList' => 42,
                    ]
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isFullTca($node)) {
            return null;
        }

        $columns = $this->extractSubArrayByKey($node->expr, 'columns');
        if (null === $columns) {
            return null;
        }

        $columnNamesWithTypeGroupAndInternalTypeDb = [];
        $this->hasAstBeenChanged = false;

        foreach ($this->extractColumnConfig($columns) as $columnName => $config) {
            if (! $config instanceof Array_) {
                continue;
            }

            if (! $this->hasKeyValuePair($config, self::TYPE, 'group')) {
                continue;
            }
            if (! $this->hasKeyValuePair($config, 'internal_type', 'db')) {
                continue;
            }

            $columnNamesWithTypeGroupAndInternalTypeDb[$columnName] = $config;

            $this->refactorWizards($config);
        }

        // now check columnsOverrides of all type=group, internal_type=db fields:
        $types = $this->extractSubArrayByKey($node->expr, 'types');
        if (null === $types) {
            return null;
        }

        foreach ($this->extractColumnConfig($types, 'columnsOverrides') as $columnOverride) {
            if (! $columnOverride instanceof Array_) {
                continue;
            }

            foreach ($columnNamesWithTypeGroupAndInternalTypeDb as $columnName => $columnConfig) {
                $overrideForColumn = $this->extractSubArrayByKey($columnOverride, $columnName);
                if (null === $overrideForColumn) {
                    continue;
                }

                $configOverride = $this->extractSubArrayByKey($overrideForColumn, 'config');
                if (null === $configOverride) {
                    continue;
                }
                if ($this->refactorWizards($configOverride)) {
                    $configOverride->items[] = new ArrayItem(new ConstFetch(new Name('false')), new String_(
                        'hideSuggest'
                    ));
                    $columnConfig->items[] = new ArrayItem(new ConstFetch(new Name('true')), new String_(
                        'hideSuggest'
                    ));
                    $this->hasAstBeenChanged = true;
                }
            }
        }

        return $this->hasAstBeenChanged ? $node : null;
    }

    private function refactorWizards(Array_ $config): bool
    {
        $wizardsArrayItem = $this->extractArrayItemByKey($config, 'wizards');
        if (null === $wizardsArrayItem) {
            return false;
        }
        $wizards = $wizardsArrayItem->value;

        if (! $wizards instanceof Array_) {
            return false;
        }

        foreach ($this->extractSubArraysWithArrayItemMatching($wizards, self::TYPE, 'suggest') as $wizard) {
            $wizardConfig = $wizard->value;
            if (! $wizardConfig instanceof Array_) {
                continue;
            }

            $typeItem = $this->extractArrayItemByKey($wizardConfig, self::TYPE);
            if (null !== $typeItem) {
                $this->removeNode($typeItem);
            }

            if (! $this->isEmpty($wizardConfig)) {
                $config->items[] = new ArrayItem($wizardConfig, new String_('suggestOptions'));
            }

            $this->removeNode($wizard);
            $this->hasAstBeenChanged = true;
        }

        if ($this->isEmpty($wizards)) {
            $this->removeNode($wizardsArrayItem);
        }
        return true;
    }

    private function isEmpty(Array_ $array): bool
    {
        $nodeEmpty = true;
        foreach ($array->items as $item) {
            if (null !== $item && ! $this->nodesToRemoveCollector->isNodeRemoved($item)) {
                if (null === $item->key) {
                    continue;
                }
                if (! Strings::startsWith($this->valueResolver->getValue($item->key), '_')) {
                    $nodeEmpty = false;
                    break;
                }
            }
        }
        return $nodeEmpty;
    }
}
