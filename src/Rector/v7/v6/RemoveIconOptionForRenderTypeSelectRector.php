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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/7.6/Breaking-70033-TcaIconOptionsForSelectFields.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v7\v6\RemoveIconOptionForRenderTypeSelectRector\RemoveIconOptionForRenderTypeSelectRectorTest
 */
final class RemoveIconOptionForRenderTypeSelectRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const SHOW_ICON_TABLE = 'showIconTable';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('TCA icon options have been removed', [
            new CodeSample(
                <<<'CODE_SAMPLE'
return [
    'columns' => [
        'foo' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'noIconsBelowSelect' => false,
            ],
        ],
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return [
    'columns' => [
        'foo' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'showIconTable' => true,
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

                $renderType = null;
                $selicon_cols = null;
                $showIconTable = null;
                $noIconsBelowSelect = null;
                $doSomething = false;

                foreach ($configValue->value->items as $configItemValueKey => $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (! $configItemValue->key instanceof Expr) {
                        continue;
                    }

                    if ($this->valueResolver->isValue($configItemValue->key, 'renderType')) {
                        $renderType = $this->valueResolver->getValue($configItemValue->value);
                    } elseif ($this->valueResolver->isValue($configItemValue->key, 'selicon_cols')) {
                        $selicon_cols = $this->valueResolver->getValue($configItemValue->value);
                        $doSomething = true;
                    } elseif ($this->valueResolver->isValue($configItemValue->key, self::SHOW_ICON_TABLE)) {
                        $showIconTable = $this->valueResolver->getValue($configItemValue->value);
                    } elseif ($this->valueResolver->isValue($configItemValue->key, 'suppress_icons')) {
                        unset($configValue->value->items[$configItemValueKey]);
                        $hasAstBeenChanged = true;
                    } elseif ($this->valueResolver->isValue($configItemValue->key, 'noIconsBelowSelect')) {
                        $noIconsBelowSelect = $this->valueResolver->getValue($configItemValue->value);
                        $doSomething = true;
                        unset($configValue->value->items[$configItemValueKey]);
                        $hasAstBeenChanged = true;
                    } elseif ($this->valueResolver->isValue($configItemValue->key, 'foreign_table_loadIcons')) {
                        unset($configValue->value->items[$configItemValueKey]);
                        $hasAstBeenChanged = true;
                    }
                }

                if (! $doSomething) {
                    continue;
                }

                if ($renderType !== 'selectSingle') {
                    continue;
                }

                if ($selicon_cols !== null && $showIconTable === null) {
                    $configValue->value->items[] = new ArrayItem($this->nodeFactory->createTrue(), new String_(
                        self::SHOW_ICON_TABLE
                    ));
                    $hasAstBeenChanged = true;
                } elseif (! $noIconsBelowSelect && $showIconTable === null) {
                    $configValue->value->items[] = new ArrayItem($this->nodeFactory->createTrue(), new String_(
                        self::SHOW_ICON_TABLE
                    ));
                    $hasAstBeenChanged = true;
                }
            }
        }

        return $hasAstBeenChanged ? $node : null;
    }
}
