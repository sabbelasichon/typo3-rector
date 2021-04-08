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
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79440-TcaChanges.html
 */
final class MigrateOptionsOfTypeGroupRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const DISABLED = 'disabled';

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

        $hasAstBeenChanged = false;
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

            foreach ($columnItem->value->items as $configValue) {
                if (null === $configValue) {
                    continue;
                }

                if (null === $configValue->key) {
                    continue;
                }

                if (! $configValue->value instanceof Array_) {
                    continue;
                }

                if (! $this->isConfigType($configValue->value, 'group')) {
                    continue;
                }

                $addFieldControls = [];
                $addFieldWizards = [];

                foreach ($configValue->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    $arrayItemKey = $configItemValue->key;
                    if (null === $arrayItemKey) {
                        continue;
                    }

                    if (! $this->valueResolver->isValues(
                        $arrayItemKey,
                        ['selectedListStyle', 'show_thumbs', 'disable_controls']
                    )) {
                        continue;
                    }

                    $this->removeNode($configItemValue);
                    $hasAstBeenChanged = true;

                    $configItemValueValue = $this->valueResolver->getValue($configItemValue->value);

                    if ($this->valueResolver->isValue($arrayItemKey, 'disable_controls') && is_string(
                        $configItemValueValue
                    )) {
                        $controls = ArrayUtility::trimExplode(',', $configItemValueValue, true);
                        foreach ($controls as $control) {
                            if ('browser' === $control) {
                                $addFieldControls['elementBrowser'][self::DISABLED] = true;
                            } elseif ('delete' === $control) {
                                $configValue->value->items[] = new ArrayItem(
                                    $this->nodeFactory->createTrue(),
                                    new String_('hideDeleteIcon')
                                );
                            } elseif ('allowedTables' === $control) {
                                $addFieldWizards['tableList'][self::DISABLED] = true;
                            } elseif ('upload' === $control) {
                                $addFieldWizards['fileUpload'][self::DISABLED] = true;
                            }
                        }
                    } elseif ($this->valueResolver->isValue(
                        $arrayItemKey,
                        'show_thumbs'
                    ) && ! (bool) $configItemValueValue) {
                        if ($this->configIsOfInternalType($configValue->value, 'db')) {
                            $addFieldWizards['recordsOverview'][self::DISABLED] = true;
                        } elseif ($this->configIsOfInternalType($configValue->value, 'file')) {
                            $addFieldWizards['fileThumbnails'][self::DISABLED] = true;
                        }
                    }
                }

                if ([] !== $addFieldControls) {
                    $configValue->value->items[] = new ArrayItem($this->nodeFactory->createArray(
                        $addFieldControls
                    ), new String_('fieldControl'));
                }

                if ([] !== $addFieldWizards) {
                    $configValue->value->items[] = new ArrayItem($this->nodeFactory->createArray(
                        $addFieldWizards
                    ), new String_('fieldWizard'));
                }
            }
        }
        return $hasAstBeenChanged ? $node : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate options if type group in TCA', [new CodeSample(<<<'CODE_SAMPLE'
return [
    'ctrl' => [],
    'columns' => [
        'image2' => [
            'config' => [
                'selectedListStyle' => 'foo',
                'type' => 'group',
                'internal_type' => 'file',
                'show_thumbs' => '0',
                'disable_controls' => 'browser'
            ],
        ],
    ],
];
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
return [
    'ctrl' => [],
    'columns' => [
        'image2' => [
            'config' => [
                'type' => 'group',
                'internal_type' => 'file',
                'fieldControl' => [
                    'elementBrowser' => ['disabled' => true]
                ],
                'fieldWizard' => [
                    'fileThumbnails' => ['disabled' => true]
                ]
            ],
        ],
    ],
];
CODE_SAMPLE
        )]);
    }
}
