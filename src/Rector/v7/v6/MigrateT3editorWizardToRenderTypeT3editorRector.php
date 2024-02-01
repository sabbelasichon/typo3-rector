<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v6;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/7.3/Deprecation-67229-TcaChanges.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v7\v6\MigrateT3editorWizardToRenderTypeT3editorRector\MigrateT3editorWizardToRenderTypeT3editorRectorTest
 */
final class MigrateT3editorWizardToRenderTypeT3editorRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('t3editor is no longer configured and enabled as wizard', [
            new CodeSample(
                <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
        'bodytext' => [
            'config' => [
                'type' => 'text',
                'rows' => '42',
                'wizards' => [
                    't3editor' => [
                        'type' => 'userFunc',
                        'userFunc' => 'TYPO3\CMS\T3editor\FormWizard->main',
                        'title' => 't3editor',
                        'icon' => 'wizard_table.gif',
                        'module' => [
                            'name' => 'wizard_table'
                        ],
                        'params' => [
                            'format' => 'html',
                            'style' => 'width:98%; height: 60%;'
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
return [
    'ctrl' => [
    ],
    'columns' => [
        'bodytext' => [
            'config' => [
                'type' => 'text',
                'rows' => '42',
                'renderType' => 't3editor',
                'format' => 'html',
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

        $columnsArrayItem = $this->extractColumns($node);

        if (! $columnsArrayItem instanceof ArrayItem) {
            return null;
        }

        $items = $columnsArrayItem->value;

        if (! $items instanceof Array_) {
            return null;
        }

        $hasAstBeenChanged = false;
        foreach ($items->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (! $fieldValue->key instanceof Expr) {
                continue;
            }

            $fieldName = $this->valueResolver->getValue($fieldValue->key);

            if ($fieldName === null) {
                continue;
            }

            if (! $fieldValue->value instanceof Array_) {
                continue;
            }

            foreach ($fieldValue->value->items as $configValue) {
                if (! $configValue instanceof ArrayItem) {
                    continue;
                }

                if (! $configValue->value instanceof Array_) {
                    continue;
                }

                foreach ($configValue->value->items as $configItemKey => $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (! $configItemValue->key instanceof Expr) {
                        continue;
                    }

                    if (! $this->valueResolver->isValue($configItemValue->key, 'wizards')) {
                        continue;
                    }

                    if (! $configItemValue->value instanceof Array_) {
                        continue;
                    }

                    $remainingWizards = count($configItemValue->value->items);
                    foreach ($configItemValue->value->items as $wizardItemKey => $wizardItemValue) {
                        if (! $wizardItemValue instanceof ArrayItem) {
                            continue;
                        }

                        if (! $wizardItemValue->value instanceof Array_) {
                            continue;
                        }

                        if (! $wizardItemValue->key instanceof Expr) {
                            continue;
                        }

                        if (! $this->valueResolver->isValue($wizardItemValue->key, 't3editor')) {
                            continue;
                        }

                        $isUserFunc = false;
                        $enableByTypeConfig = false;
                        $format = null;
                        foreach ($wizardItemValue->value->items as $wizardItemSubValue) {
                            if (! $wizardItemSubValue instanceof ArrayItem) {
                                continue;
                            }

                            if (! $wizardItemSubValue->key instanceof Expr) {
                                continue;
                            }

                            if ($this->valueResolver->isValue(
                                $wizardItemSubValue->key,
                                'userFunc'
                            ) && $this->valueResolver->isValue(
                                $wizardItemSubValue->value,
                                'TYPO3\CMS\T3editor\FormWizard->main'
                            )) {
                                $isUserFunc = true;
                            } elseif ($this->valueResolver->isValue(
                                $wizardItemSubValue->key,
                                'enableByTypeConfig'
                            ) && $this->valueResolver->isValue($wizardItemSubValue->value, 'enableByTypeConfig')) {
                                $enableByTypeConfig = true;
                            } elseif ($wizardItemSubValue->value instanceof Array_ && $this->valueResolver->isValue(
                                $wizardItemSubValue->key,
                                'params'
                            )) {
                                foreach ($wizardItemSubValue->value->items as $paramsValue) {
                                    if (! $paramsValue instanceof ArrayItem) {
                                        continue;
                                    }

                                    if (! $paramsValue->key instanceof Expr) {
                                        continue;
                                    }

                                    if ($this->valueResolver->isValue($paramsValue->key, 'format')) {
                                        $format = $paramsValue->value;
                                    }
                                }
                            }
                        }

                        if ($isUserFunc && ! $enableByTypeConfig) {
                            unset($configItemValue->value->items[$wizardItemKey]);
                            $hasAstBeenChanged = true;
                            $configValue->value->items[] = new ArrayItem(new String_('t3editor'), new String_(
                                'renderType'
                            ));

                            if ($format instanceof Expr) {
                                $configValue->value->items[] = new ArrayItem($format, new String_('format'));
                            }

                            --$remainingWizards;
                        }
                    }

                    if ($remainingWizards === 0) {
                        unset($configValue->value->items[$configItemKey]);
                        $hasAstBeenChanged = true;
                    }
                }
            }
        }

        return $hasAstBeenChanged ? $node : null;
    }
}
