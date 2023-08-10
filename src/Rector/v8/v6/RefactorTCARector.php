<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79440-TcaChanges.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v6\RefactorTCARector\RefactorTCARectorTest
 */
final class RefactorTCARector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var array<string, string>
     */
    private const MAP_WIZARD_TO_FIELD_CONTROL = [
        'edit' => 'editPopup',
        'add' => 'addRecord',
        'list' => 'listModule',
        'link' => 'linkPopup',
        'RTE' => 'fullScreenRichtext',
    ];

    /**
     * @var array<string, string>
     */
    private const MAP_WIZARD_TO_RENDER_TYPE = [
        'table' => 'textTable',
        'colorChoice' => 'colorpicker',
        'link' => 'inputLink',
    ];

    /**
     * @var array<string, string>
     */
    private const MAP_WIZARD_TO_CUSTOM_TYPE = [
        'select' => 'valuePicker',
        'suggest' => 'suggestOptions',
        'angle' => 'slider',
    ];

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

        $columns = $this->extractColumns($node);

        if (! $columns instanceof ArrayItem) {
            return null;
        }

        $items = $columns->value;

        if (! $items instanceof Array_) {
            return null;
        }

        $this->addFieldControlInsteadOfWizardsAddListEdit($items);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('A lot of different TCA changes', [
            new CodeSample(
                <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
        'text_17' => [
            'label' => 'text_17',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '5',
                'wizards' => [
                    'table' => [
                        'notNewRecords' => 1,
                        'type' => 'script',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.table',
                        'icon' => 'content-table',
                        'module' => [
                            'name' => 'wizard_table'
                        ],
                        'params' => [
                            'xmlOutput' => 0
                        ]
                    ],
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
        'text_17' => [
            'label' => 'text_17',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '5',
                'renderType' => 'textTable',
            ],
        ],
    ],
];
CODE_SAMPLE
            ),
        ]);
    }

    private function addFieldControlInsteadOfWizardsAddListEdit(Array_ $itemsArray): void
    {
        foreach ($itemsArray->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if ($fieldValue->key === null) {
                continue;
            }

            $fieldName = $this->valueResolver->getValue($fieldValue->key);

            if ($fieldName === null) {
                continue;
            }

            if (! $fieldValue->value instanceof Array_) {
                continue;
            }

            $fieldValueArray = $fieldValue->value;
            foreach ($fieldValueArray->items as $configValue) {
                if (! $configValue instanceof ArrayItem) {
                    continue;
                }

                if (! $configValue->value instanceof Array_) {
                    continue;
                }

                /** @var Array_ $configValueArray */
                $configValueArray = $configValue->value;

                // Refactor input type
                if ($this->isConfigType($configValueArray, 'input') && ! $this->hasRenderType($configValueArray)) {
                    $this->refactorRenderTypeInputDateTime($configValue);
                }

                foreach ($configValueArray->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if ($configItemValue->key === null) {
                        continue;
                    }

                    if (! $this->valueResolver->isValues($configItemValue->key, ['wizards'])) {
                        continue;
                    }

                    if (! $configItemValue->value instanceof Array_) {
                        continue;
                    }

                    $fieldControl = [];
                    $customTypeOptions = [];

                    $remainingWizards = count($configItemValue->value->items);
                    foreach ($configItemValue->value->items as $wizardItemValue) {
                        if (! $wizardItemValue instanceof ArrayItem) {
                            continue;
                        }

                        if (! $wizardItemValue->key instanceof Expr) {
                            continue;
                        }

                        /** @var Expr $wizardItemValueKey */
                        $wizardItemValueKey = $wizardItemValue->key;

                        $validWizard = $this->isValidWizard($wizardItemValue);
                        if ($validWizard ||
                            Strings::startsWith($this->valueResolver->getValue($wizardItemValueKey), '_')
                        ) {
                            --$remainingWizards;
                        }

                        if (! $validWizard) {
                            continue;
                        }

                        $this->removeNode($wizardItemValue);

                        if (! $wizardItemValue->value instanceof Array_) {
                            continue;
                        }

                        $wizardItemValueKey = $this->valueResolver->getValue($wizardItemValueKey);

                        if ($wizardItemValueKey === null) {
                            continue;
                        }

                        $fieldControlKey = null;
                        if (array_key_exists($wizardItemValueKey, self::MAP_WIZARD_TO_FIELD_CONTROL)) {
                            $fieldControlKey = self::MAP_WIZARD_TO_FIELD_CONTROL[$wizardItemValueKey];

                            if ($wizardItemValueKey !== 'link') {
                                $fieldControl[$fieldControlKey] = [
                                    'disabled' => false,
                                ];
                            }
                        }

                        if (array_key_exists(
                            $wizardItemValueKey,
                            self::MAP_WIZARD_TO_RENDER_TYPE
                        ) && $this->extractArrayItemByKey($configValueArray, 'renderType') === null) {
                            $configValueArray->items[] = new ArrayItem(new String_(
                                self::MAP_WIZARD_TO_RENDER_TYPE[$wizardItemValueKey]
                            ), new String_('renderType'));
                        }

                        $selectOptions = [];

                        foreach ($wizardItemValue->value->items as $wizardItemSubValue) {
                            if (! $wizardItemSubValue instanceof ArrayItem) {
                                continue;
                            }

                            if ($wizardItemSubValue->key === null) {
                                continue;
                            }

                            // Configuration of slider wizard
                            if ($wizardItemValueKey === 'angle' && ! $this->valueResolver->isValue(
                                $wizardItemSubValue->key,
                                'type'
                            )) {
                                $sliderValue = $this->valueResolver->getValue($wizardItemSubValue->value);
                                if ($sliderValue) {
                                    $customTypeOptions[$this->valueResolver->getValue(
                                        $wizardItemSubValue->key
                                    )] = $sliderValue;
                                }
                            } elseif ($wizardItemValueKey === 'select' && $this->valueResolver->isValue(
                                $wizardItemSubValue->key,
                                'items'
                            )) {
                                $selectOptions[$this->getValue($wizardItemSubValue->key)] = $this->getValue(
                                    $wizardItemSubValue->value
                                );
                            }

                            if ($wizardItemSubValue->value instanceof Array_ && $this->valueResolver->isValue(
                                $wizardItemSubValue->key,
                                'params'
                            )) {
                                foreach ($wizardItemSubValue->value->items as $paramsValue) {
                                    if (! $paramsValue instanceof ArrayItem) {
                                        continue;
                                    }

                                    if ($paramsValue->key === null) {
                                        continue;
                                    }

                                    $value = $this->valueResolver->getValue($paramsValue->value);
                                    if ($value === null) {
                                        continue;
                                    }

                                    if ($fieldControlKey !== null && $this->valueResolver->isValues($paramsValue->key, [
                                        'table',
                                        'pid',
                                        'setValue',
                                        'blindLinkOptions',
                                        'JSopenParams',
                                        'blindLinkFields',
                                        'allowedExtensions',
                                    ])) {
                                        $paramsValueKey = $this->valueResolver->getValue($paramsValue->key);
                                        if ($paramsValueKey !== null) {
                                            if ($paramsValueKey === 'JSopenParams') {
                                                $paramsValueKey = 'windowOpenParameters';
                                            }
                                            $fieldControl[$fieldControlKey]['options'][$paramsValueKey] = $value;
                                        }
                                    }
                                }
                            } elseif ($fieldControlKey !== null && $this->valueResolver->isValue(
                                $wizardItemSubValue->key,
                                'title'
                            )) {
                                $value = $this->valueResolver->getValue($wizardItemSubValue->value);
                                if ($value === null) {
                                    continue;
                                }
                                $fieldControl[$fieldControlKey]['options'][$this->valueResolver->getValue(
                                    $wizardItemSubValue->key
                                )] = $value;
                            }
                        }

                        if ($selectOptions !== [] && $this->extractArrayItemByKey(
                            $configValueArray,
                            self::MAP_WIZARD_TO_CUSTOM_TYPE['select']
                        ) === null) {
                            $configValueArray->items[] = new ArrayItem($this->nodeFactory->createArray(
                                $selectOptions
                            ), new String_(self::MAP_WIZARD_TO_CUSTOM_TYPE['select']));
                        }

                        if ($customTypeOptions !== [] && array_key_exists(
                            $wizardItemValueKey,
                            self::MAP_WIZARD_TO_CUSTOM_TYPE
                        ) && $this->extractArrayItemByKey(
                            $configValueArray,
                            self::MAP_WIZARD_TO_CUSTOM_TYPE[$wizardItemValueKey]
                        ) === null) {
                            $configValueArray->items[] = new ArrayItem($this->nodeFactory->createArray(
                                $customTypeOptions
                            ), new String_(self::MAP_WIZARD_TO_CUSTOM_TYPE[$wizardItemValueKey]));
                        }
                    }

                    $existingFieldControl = $this->extractArrayItemByKey($configValueArray, 'fieldControl');

                    if ($existingFieldControl === null && $fieldControl !== []) {
                        $configValueArray->items[] = new ArrayItem($this->nodeFactory->createArray(
                            $fieldControl
                        ), new String_('fieldControl'));
                    } elseif ($fieldControl !== [] && $existingFieldControl instanceof ArrayItem) {
                        foreach ($fieldControl as $fieldControlKey => $fieldControlValue) {
                            if ($this->extractArrayItemByKey($existingFieldControl->value, $fieldControlKey) !== null) {
                                continue;
                            }

                            if (! $existingFieldControl->value instanceof Array_) {
                                continue;
                            }

                            $existingFieldControl->value->items[] = new ArrayItem($this->nodeFactory->createArray(
                                $fieldControlValue
                            ), new String_($fieldControlKey));
                        }
                    }

                    if ($remainingWizards === 0) {
                        $this->removeNode($configItemValue);
                    }
                }
            }
        }
    }

    private function refactorRenderTypeInputDateTime(ArrayItem $configValueArrayItem): void
    {
        if (! $configValueArrayItem->value instanceof Array_) {
            return;
        }

        foreach ($configValueArrayItem->value->items as $configItemValue) {
            if (! $configItemValue instanceof ArrayItem) {
                continue;
            }

            if ($configItemValue->key === null) {
                continue;
            }

            if (! $this->valueResolver->isValue($configItemValue->key, 'eval')) {
                continue;
            }

            $eval = $this->valueResolver->getValue($configItemValue->value);

            if ($eval === null) {
                continue;
            }

            $eval = ArrayUtility::trimExplode(',', $eval, true);
            if (in_array('date', $eval, true)
                || in_array('datetime', $eval, true)
                || in_array('time', $eval, true)
                || in_array('timesec', $eval, true)
            ) {
                $configValueArrayItem->value->items[] = new ArrayItem(new String_('inputDateTime'), new String_(
                    'renderType'
                ));
            }
        }
    }

    /**
     * @param mixed $wizardItemValue
     */
    private function isValidWizard($wizardItemValue): bool
    {
        return $this->valueResolver->isValues($wizardItemValue->key, array_merge(
            array_keys(self::MAP_WIZARD_TO_FIELD_CONTROL),
            array_keys(self::MAP_WIZARD_TO_RENDER_TYPE),
            array_keys(self::MAP_WIZARD_TO_CUSTOM_TYPE)
        ));
    }
}
