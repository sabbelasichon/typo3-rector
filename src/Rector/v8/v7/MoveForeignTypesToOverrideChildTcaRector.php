<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80000-InlineOverrideChildTca.html?highlight=foreign_types
 */
final class MoveForeignTypesToOverrideChildTcaRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const FOREIGN_TYPES = 'foreign_types';

    /**
     * @var string
     */
    private const FOREIGN_SELECTOR_FIELDTCAOVERRIDE = 'foreign_selector_fieldTcaOverride';

    /**
     * @var string
     */
    private const FOREIGN_SELECTOR = 'foreign_selector';

    /**
     * @var string
     */
    private const FOREIGN_RECORD_DEFAULTS = 'foreign_record_defaults';

    /**
     * @var string
     */
    private const OVERRIDE_CHILD_TCA = 'overrideChildTca';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('TCA InlineOverrideChildTca', [
            new CodeSample(<<<'CODE_SAMPLE'
return [
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'inline',
                'foreign_types' => [
                    'aForeignType' => [
                        'showitem' => 'aChildField',
                    ],
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
return [
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'inline',
                'overrideChildTca' => [
                    'types' => [
                        'aForeignType' => [
                            'showitem' => 'aChildField',
                        ],
                    ],
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
     * @return ?Return_
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isTca($node)) {
            return null;
        }

        $columns = $this->extractColumns($node);

        if (! $columns instanceof ArrayItem) {
            return null;
        }

        if (! $columns->value instanceof Array_) {
            return null;
        }

        $hasAstBeenChanged = false;
        foreach ($this->extractColumnConfig($columns->value) as $columnConfig) {
            //handle the special case of ExtensionManagementUtility::getFileFieldTCAConfig
            $columnConfig = $this->extractConfigFromGetFileFieldTcaConfig($columnConfig);

            if (! $columnConfig instanceof Array_) {
                continue;
            }

            $foreignTypesArrayItem = null;
            $foreignRecordDefaults = null;
            $foreignSelector = null;
            $overrideChildTcaNode = null;
            $foreignSelectorOverrideNode = null;
            foreach ($columnConfig->items as $configItemValue) {
                if (! $configItemValue instanceof ArrayItem) {
                    continue;
                }

                if (null === $configItemValue->key) {
                    continue;
                }

                if ($this->valueResolver->isValue($configItemValue->key, self::FOREIGN_TYPES)) {
                    $foreignTypesArrayItem = $configItemValue;
                } elseif ($this->valueResolver->isValue($configItemValue->key, self::FOREIGN_RECORD_DEFAULTS)) {
                    $foreignRecordDefaults = $configItemValue;
                } elseif ($this->valueResolver->isValue($configItemValue->key, self::FOREIGN_SELECTOR)) {
                    $foreignSelector = $configItemValue->value;
                } elseif ($this->valueResolver->isValue(
                    $configItemValue->key,
                    self::FOREIGN_SELECTOR_FIELDTCAOVERRIDE
                )) {
                    $foreignSelectorOverrideNode = $configItemValue;
                } elseif ($this->valueResolver->isValue($configItemValue->key, self::OVERRIDE_CHILD_TCA)) {
                    $overrideChildTcaNode = $configItemValue->value;
                }
            }

            // don't search further if no foreign_types is configured
            if (null === $foreignSelectorOverrideNode && null === $foreignTypesArrayItem && null === $foreignRecordDefaults) {
                continue;
            }

            if (null !== $overrideChildTcaNode && ! $overrideChildTcaNode instanceof Array_) {
                continue;
            }

            if (null === $overrideChildTcaNode) {
                $overrideChildTcaNode = new Array_();
                $columnConfig->items[] = new ArrayItem($overrideChildTcaNode, new String_(
                    self::OVERRIDE_CHILD_TCA
                ));
            }

            if (null !== $foreignTypesArrayItem && $foreignTypesArrayItem->value instanceof Array_) {
                $this->injectOverrideChildTca($overrideChildTcaNode, 'types', $foreignTypesArrayItem->value);
                $this->removeNode($foreignTypesArrayItem);
                $hasAstBeenChanged = true;
            }

            if (null !== $foreignSelectorOverrideNode && $foreignSelectorOverrideNode->value instanceof Array_ && $foreignSelector instanceof String_) {
                $columnItem = new Array_([
                    new ArrayItem($foreignSelectorOverrideNode->value, new String_($foreignSelector->value)),
                ]);
                $this->injectOverrideChildTca($overrideChildTcaNode, 'columns', $columnItem);
                $this->removeNode($foreignSelectorOverrideNode);
                $hasAstBeenChanged = true;
            }

            if (null !== $foreignRecordDefaults && $foreignRecordDefaults->value instanceof Array_) {
                $newOverrideColumns = new Array_();
                foreach ($foreignRecordDefaults->value->items as $item) {
                    if (! $item instanceof ArrayItem) {
                        continue;
                    }
                    $value = new Array_([
                        new ArrayItem(
                            new Array_([new ArrayItem($item->value, new String_('default'))]),
                            new String_('config')
                        ),
                    ]);
                    $newOverrideColumns->items[] = new ArrayItem($value, $item->key);
                }

                $this->injectOverrideChildTca($overrideChildTcaNode, 'columns', $newOverrideColumns);
                $this->removeNode($foreignRecordDefaults);
                $hasAstBeenChanged = true;
            }
        }

        return $hasAstBeenChanged ? $node : null;
    }

    private function extractConfigFromGetFileFieldTcaConfig(Node $columnConfig): Node
    {
        if ($columnConfig instanceof StaticCall) {
            if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $columnConfig,
                new ObjectType(ExtensionManagementUtility::class)
            )) {
                return $columnConfig;
            }
            if (! $this->isName($columnConfig->name, 'getFileFieldTCAConfig')) {
                return $columnConfig;
            }
            if (count($columnConfig->args) < 2) {
                return $columnConfig;
            }
            if (! $columnConfig->args[1]->value instanceof Array_) {
                return $columnConfig;
            }
            return $columnConfig->args[1]->value;
        }
        return $columnConfig;
    }

    private function extractExistingOverrideChildTca(Array_ $overrideChildTcaNode, string $key): ?ArrayItem
    {
        $overrideChildTcaTypesArrayItem = null;
        foreach ($overrideChildTcaNode->items as $overrideChildTcaOption) {
            if (! $overrideChildTcaOption instanceof ArrayItem) {
                continue;
            }
            if (null === $overrideChildTcaOption->key) {
                continue;
            }
            if ($this->valueResolver->isValue($overrideChildTcaOption->key, $key)) {
                $overrideChildTcaTypesArrayItem = $overrideChildTcaOption;
            }
        }
        return $overrideChildTcaTypesArrayItem;
    }

    private function injectOverrideChildTca(
        Array_ $overrideChildTcaNode,
        string $overrideKey,
        Array_ $overrideValue
    ): void {
        $newOverrideChildTcaSetting = $this->extractExistingOverrideChildTca($overrideChildTcaNode, $overrideKey);
        if (null === $newOverrideChildTcaSetting) {
            $newOverrideChildTcaSetting = new ArrayItem($overrideValue, new String_($overrideKey));
            $overrideChildTcaNode->items[] = $newOverrideChildTcaSetting;
        } else {
            if (! $newOverrideChildTcaSetting->value instanceof Array_) {
                // do not alter overrideChildTca nodes that are not an array (which would be invalid tca, but lets be sure here)
                return;
            }
            foreach ($overrideValue->items as $item) {
                $newOverrideChildTcaSetting->value->items[] = $item;
            }
        }
    }
}
