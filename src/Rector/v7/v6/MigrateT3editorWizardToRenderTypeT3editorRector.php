<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v6;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.3/Deprecation-67229-TcaChanges.html
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
            new CodeSample(<<<'CODE_SAMPLE'
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
                , <<<'CODE_SAMPLE'
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

        $hasAstBeenChanged = false;
        foreach ($items->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (null === $fieldValue->key) {
                continue;
            }

            $fieldName = $this->valueResolver->getValue($fieldValue->key);

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

                foreach ($configValue->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (null === $configItemValue->key) {
                        continue;
                    }

                    if (! $this->valueResolver->isValue($configItemValue->key, 'wizards')) {
                        continue;
                    }

                    if (! $configItemValue->value instanceof Array_) {
                        continue;
                    }

                    $remainingWizards = count($configItemValue->value->items);
                    foreach ($configItemValue->value->items as $wizardItemValue) {
                        if (! $wizardItemValue instanceof ArrayItem) {
                            continue;
                        }

                        if (! $wizardItemValue->value instanceof Array_) {
                            continue;
                        }

                        if (null === $wizardItemValue->key) {
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

                            if (null === $wizardItemSubValue->key) {
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

                                    if (null === $paramsValue->key) {
                                        continue;
                                    }

                                    if ($this->valueResolver->isValue($paramsValue->key, 'format')) {
                                        $format = $paramsValue->value;
                                    }
                                }
                            }
                        }

                        if ($isUserFunc && ! $enableByTypeConfig) {
                            $this->removeNode($wizardItemValue);
                            $hasAstBeenChanged = true;
                            $configValue->value->items[] = new ArrayItem(new String_('t3editor'), new String_(
                                'renderType'
                            ));

                            if (null !== $format) {
                                $configValue->value->items[] = new ArrayItem($format, new String_('format'));
                            }
                            --$remainingWizards;
                        }
                    }

                    if (0 === $remainingWizards) {
                        $this->removeNode($configItemValue);
                        $hasAstBeenChanged = true;
                    }
                }
            }
        }
        return $hasAstBeenChanged ? $node : null;
    }
}
