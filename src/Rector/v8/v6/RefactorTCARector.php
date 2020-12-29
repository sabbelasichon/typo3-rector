<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79440-TcaChanges.html
 */
final class RefactorTCARector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var array
     */
    private const MAP_WIZARD_TO_FIELD_CONTROL = [
        'edit' => 'editPopup',
        'add' => 'addRecord',
        'list' => 'listModule',
        'link' => 'linkPopup',
    ];

    /**
     * @var array
     */
    private const MAP_WIZARD_TO_RENDER_TYPE = [
        'table' => 'textTable',
        'colorChoice' => 'colorpicker',
        'link' => 'inputLink',
    ];

    /**
     * @var array
     */
    private const MAP_WIZARD_TO_CUSTOM_TYPE = [
        'select' => 'valuePicker',
        'suggest' => 'suggestOptions',
        'angle' => 'slider',
    ];

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('A lot of different TCA changes', [
            new CodeSample(<<<'PHP'
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
PHP
                , <<<'PHP'
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
PHP
            ),
        ]);
    }

    /**
     * @return string[]
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
        if (! $this->isTca($node)) {
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

    private function addFieldControlInsteadOfWizardsAddListEdit(Array_ $items): void
    {
        foreach ($items->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (null === $fieldValue->key) {
                continue;
            }

            $fieldName = $this->getValue($fieldValue->key);

            if (null === $fieldName) {
                continue;
            }

            if (! $fieldValue->value instanceof Array_) {
                continue;
            }

            foreach ($fieldValue->value->items as $configValue) {
                if (null === $configValue) {
                    continue;
                }

                if (! $configValue->value instanceof Array_) {
                    continue;
                }

                // Refactor input type
                if ($this->isConfigType($configValue->value, 'input') && ! $this->hasRenderType($configValue->value)) {
                    $this->refactorRenderTypeInputDateTime($configValue);
                }

                foreach ($configValue->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (null === $configItemValue->key) {
                        continue;
                    }

                    if (! $this->isValues($configItemValue->key, ['wizards', 'RTE'])) {
                        continue;
                    }

                    if (! $configItemValue->value instanceof Array_) {
                        continue;
                    }

                    $fieldControl = [];
                    $customTypeOptions = [];

                    if ($this->isValue($configItemValue->key, 'RTE')) {
                        $fieldControl['fullScreenRichtext']['disabled'] = false;
                    }

                    $remainingWizards = count($configItemValue->value->items);
                    foreach ($configItemValue->value->items as $wizardItemValue) {
                        if (null === $wizardItemValue) {
                            continue;
                        }

                        if (null === $wizardItemValue->key) {
                            continue;
                        }

                        --$remainingWizards;

                        if (! $this->isValues(
                            $wizardItemValue->key,
                            array_merge(
                                array_keys(self::MAP_WIZARD_TO_FIELD_CONTROL),
                                array_keys(self::MAP_WIZARD_TO_RENDER_TYPE),
                                array_keys(self::MAP_WIZARD_TO_CUSTOM_TYPE)
                            )
                        )) {
                            continue;
                        }

                        $this->removeNode($wizardItemValue);

                        if (! $wizardItemValue->value instanceof Array_) {
                            continue;
                        }

                        $wizardItemValueKey = $this->getValue($wizardItemValue->key);

                        if (null === $wizardItemValueKey) {
                            continue;
                        }

                        $fieldControlKey = null;
                        if (array_key_exists($wizardItemValueKey, self::MAP_WIZARD_TO_FIELD_CONTROL)) {
                            $fieldControlKey = self::MAP_WIZARD_TO_FIELD_CONTROL[$wizardItemValueKey];

                            if ('link' !== $wizardItemValueKey) {
                                $fieldControl[$fieldControlKey] = [
                                    'disabled' => false,
                                ];
                            }
                        }

                        if (array_key_exists($wizardItemValueKey, self::MAP_WIZARD_TO_RENDER_TYPE)) {
                            $configValue->value->items[] = new ArrayItem(new String_(
                                self::MAP_WIZARD_TO_RENDER_TYPE[$wizardItemValueKey]
                            ), new String_('renderType'));
                        }

                        foreach ($wizardItemValue->value->items as $wizardItemSubValue) {
                            if (! $wizardItemSubValue instanceof ArrayItem) {
                                continue;
                            }

                            if (null === $wizardItemSubValue->key) {
                                continue;
                            }

                            // Configuration of slider wizard
                            if ('angle' === $wizardItemValueKey && ! $this->isValue($wizardItemSubValue->key, 'type')) {
                                $sliderValue = $this->getValue($wizardItemSubValue->value);
                                if ($sliderValue) {
                                    $customTypeOptions[$this->getValue($wizardItemSubValue->key)] = $sliderValue;
                                }
                            } elseif ('select' === $wizardItemValueKey && $this->isValue(
                                $wizardItemSubValue->key,
                                'items'
                            )) {
                                $configValue->value->items[] = new ArrayItem(new Array_([
                                    new ArrayItem($wizardItemSubValue->value, $wizardItemSubValue->key),
                                ]), new String_(self::MAP_WIZARD_TO_CUSTOM_TYPE[$wizardItemValueKey]));
                            } elseif ('suggest' === $wizardItemValueKey && $this->isValue(
                                $wizardItemSubValue->key,
                                'default'
                            )) {
                                $configValue->value->items[] = new ArrayItem(new Array_([
                                    new ArrayItem($wizardItemSubValue->value, $wizardItemSubValue->key),
                                ]), new String_(self::MAP_WIZARD_TO_CUSTOM_TYPE[$wizardItemValueKey]));
                            }

                            if ($wizardItemSubValue->value instanceof Array_ && $this->isValue(
                                    $wizardItemSubValue->key,
                                    'params'
                                )) {
                                foreach ($wizardItemSubValue->value->items as $paramsValue) {
                                    if (! $paramsValue instanceof ArrayItem) {
                                        continue;
                                    }

                                    if (null === $paramsValue->key) {
                                        continue;
                                    }

                                    $value = $this->getValue($paramsValue->value);
                                    if (null === $value) {
                                        continue;
                                    }

                                    if (null !== $fieldControlKey && $this->isValues(
                                        $paramsValue->key,
                                        [
                                            'table',
                                            'pid',
                                            'setValue',
                                            'blindLinkOptions',
                                            'JSopenParams',
                                            'blindLinkFields',
                                            'allowedExtensions',
                                        ]
                                    )) {
                                        $paramsValueKey = $this->getValue($paramsValue->key);
                                        if (null !== $paramsValueKey) {
                                            if ('JSopenParams' === $paramsValueKey) {
                                                $paramsValueKey = 'windowOpenParameters';
                                            }
                                            $fieldControl[$fieldControlKey]['options'][$paramsValueKey] = $value;
                                        }
                                    }
                                }
                            } elseif (null !== $fieldControlKey && $this->isValue($wizardItemSubValue->key, 'title')) {
                                $value = $this->getValue($wizardItemSubValue->value);
                                if (null === $value) {
                                    continue;
                                }
                                $fieldControl[$fieldControlKey]['options'][$this->getValue(
                                    $wizardItemSubValue->key
                                )] = $value;
                            }
                        }

                        if ([] !== $customTypeOptions && array_key_exists(
                            $wizardItemValueKey,
                            self::MAP_WIZARD_TO_CUSTOM_TYPE
                        )) {
                            $configValue->value->items[] = new ArrayItem($this->createArray(
                                $customTypeOptions
                            ), new String_(self::MAP_WIZARD_TO_CUSTOM_TYPE[$wizardItemValueKey]));
                        }
                    }

                    if ([] !== $fieldControl) {
                        $configValue->value->items[] = new ArrayItem($this->createArray($fieldControl), new String_(
                            'fieldControl'
                        ));
                    }

                    if (0 === $remainingWizards) {
                        $this->removeNode($configItemValue);
                    }
                }
            }
        }
    }

    private function refactorRenderTypeInputDateTime(ArrayItem $configValue): void
    {
        if (! $configValue->value instanceof Array_) {
            return;
        }

        foreach ($configValue->value->items as $configItemValue) {
            if (! $configItemValue instanceof ArrayItem) {
                continue;
            }

            if (null === $configItemValue->key) {
                continue;
            }

            if (! $this->isValue($configItemValue->key, 'eval')) {
                continue;
            }

            $eval = $this->getValue($configItemValue->value);

            if (null === $eval) {
                continue;
            }

            $eval = GeneralUtility::trimExplode(',', $eval, true);
            if (in_array('date', $eval, true)
                || in_array('datetime', $eval, true)
                || in_array('time', $eval, true)
                || in_array('timesec', $eval, true)
            ) {
                $configValue->value->items[] = new ArrayItem(new String_('inputDateTime'), new String_('renderType'));
            }
        }
    }
}
