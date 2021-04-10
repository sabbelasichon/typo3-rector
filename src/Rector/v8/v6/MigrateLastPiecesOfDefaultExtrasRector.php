<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\ArrayUtility;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79440-TcaChanges.html
 */
final class MigrateLastPiecesOfDefaultExtrasRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var bool
     */
    private $hasAstBeenChanged = false;

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

        $columnItems = $columns->value;

        if (! $columnItems instanceof Array_) {
            return null;
        }

        $this->refactorDefaultExtras($columnItems);

        $types = $this->extractTypes($node);
        if (! $types instanceof ArrayItem) {
            return $this->hasAstBeenChanged ? $node : null;
        }

        $typesItems = $types->value;

        if (! $typesItems instanceof Array_) {
            return $this->hasAstBeenChanged ? $node : null;
        }

        foreach ($typesItems->items as $typesItem) {
            if (! $typesItem instanceof ArrayItem) {
                continue;
            }

            if (null === $typesItem->key) {
                continue;
            }

            if (! $typesItem->value instanceof Array_) {
                continue;
            }

            foreach ($typesItem->value->items as $configValue) {
                if (null === $configValue) {
                    continue;
                }

                if (null === $configValue->key) {
                    continue;
                }

                if (! $this->valueResolver->isValue($configValue->key, 'columnsOverrides')) {
                    continue;
                }

                if (! $configValue->value instanceof Array_) {
                    continue;
                }

                $this->refactorDefaultExtras($configValue->value);
            }
        }

        return $this->hasAstBeenChanged ? $node : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate last pieces of default extras', [new CodeSample(<<<'CODE_SAMPLE'
return [
            'ctrl' => [],
            'columns' => [
                'constants' => [
                    'label' => 'Foo',
                    'config' => [
                        'type' => 'text',
                        'cols' => 48,
                        'rows' => 15,
                    ],
                    'defaultExtras' => 'rte_only:nowrap:enable-tab:fixed-font'
                ],
            ],
            'types' => [
                'myType' => [
                    'columnsOverrides' => [
                        'constants' => [
                            'label' => 'Foo',
                            'config' => [
                                'type' => 'text',
                                'cols' => 48,
                                'rows' => 15,
                            ],
                            'defaultExtras' => 'rte_only:nowrap:enable-tab:fixed-font'
                        ],
                    ],
                ],
            ],
        ];
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
return [
            'ctrl' => [],
            'columns' => [
                'constants' => [
                    'label' => 'Foo',
                    'config' => [
                        'type' => 'text',
                        'cols' => 48,
                        'rows' => 15,
                        'wrap' => 'off',
                        'enableTabulator' => true,
                        'fixedFont' => true,
                    ]
                ],
            ],
            'types' => [
                'myType' => [
                    'columnsOverrides' => [
                        'constants' => [
                            'label' => 'Foo',
                            'config' => [
                                'type' => 'text',
                                'cols' => 48,
                                'rows' => 15,
                                'wrap' => 'off',
                                'enableTabulator' => true,
                                'fixedFont' => true,
                            ]
                        ],
                    ],
                ],
            ],
        ];
CODE_SAMPLE
        )]);
    }

    private function refactorDefaultExtras(Array_ $columnItems): void
    {
        foreach ($columnItems->items as $columnItem) {
            if (! $columnItem instanceof ArrayItem) {
                continue;
            }

            if (null === $columnItem->key) {
                continue;
            }

            if (! $columnItem->value instanceof Array_) {
                continue;
            }

            $additionalConfigItems = [];

            foreach ($columnItem->value->items as $configValue) {
                if (null === $configValue) {
                    continue;
                }

                if (null === $configValue->key) {
                    continue;
                }

                if (! $this->valueResolver->isValue($configValue->key, 'defaultExtras')) {
                    continue;
                }

                $defaultExtras = $this->valueResolver->getValue($configValue->value);

                if (! is_string($defaultExtras)) {
                    continue;
                }

                $defaultExtrasArray = ArrayUtility::trimExplode(':', $defaultExtras, true);

                foreach ($defaultExtrasArray as $defaultExtrasSetting) {
                    if ('nowrap' === $defaultExtrasSetting) {
                        $additionalConfigItems[] = new ArrayItem(new String_('off'), new String_('wrap'));
                    } elseif ('enable-tab' === $defaultExtrasSetting) {
                        $additionalConfigItems[] = new ArrayItem($this->nodeFactory->createTrue(), new String_(
                            'enableTabulator'
                        ));
                    } elseif ('fixed-font' === $defaultExtrasSetting) {
                        $additionalConfigItems[] = new ArrayItem($this->nodeFactory->createTrue(), new String_(
                            'fixedFont'
                        ));
                    }
                }

                // Remove the defaultExtras
                $this->removeNode($configValue);
            }

            if ([] !== $additionalConfigItems) {
                $this->hasAstBeenChanged = true;

                foreach ($columnItem->value->items as $configValue) {
                    if (null === $configValue) {
                        continue;
                    }

                    if (null === $configValue->key) {
                        continue;
                    }

                    if (! $this->valueResolver->isValue($configValue->key, 'config')) {
                        continue;
                    }

                    if (! $configValue->value instanceof Array_) {
                        continue;
                    }

                    foreach ($additionalConfigItems as $additionalConfigItem) {
                        $configValue->value->items[] = $additionalConfigItem;
                    }
                }
            }
        }
    }
}
