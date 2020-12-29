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
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.6/Breaking-70033-TcaIconOptionsForSelectFields.html
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
            new CodeSample(<<<'PHP'
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
PHP
                , <<<'PHP'
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

                $renderType = null;
                $selicon_cols = null;
                $showIconTable = null;
                $noIconsBelowSelect = null;
                foreach ($configValue->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (null === $configItemValue->key) {
                        continue;
                    }

                    if ($this->isValue($configItemValue->key, 'renderType')) {
                        $renderType = $this->getValue($configItemValue->value);
                    } elseif ($this->isValue($configItemValue->key, 'selicon_cols')) {
                        $selicon_cols = $this->getValue($configItemValue->value);
                    } elseif ($this->isValue($configItemValue->key, self::SHOW_ICON_TABLE)) {
                        $showIconTable = $this->getValue($configItemValue->value);
                    } elseif ($this->isValue($configItemValue->key, 'suppress_icons')) {
                        $this->removeNode($configItemValue);
                    } elseif ($this->isValue($configItemValue->key, 'noIconsBelowSelect')) {
                        $noIconsBelowSelect = $this->getValue($configItemValue->value);
                        $this->removeNode($configItemValue);
                    } elseif ($this->isValue($configItemValue->key, 'foreign_table_loadIcons')) {
                        $this->removeNode($configItemValue);
                    }
                }

                if (null === $renderType || 'selectSingle' !== $renderType) {
                    continue;
                }

                if (null !== $selicon_cols && null === $showIconTable) {
                    $configValue->value->items[] = new ArrayItem($this->createTrue(), new String_(
                        self::SHOW_ICON_TABLE
                    ));
                } elseif (! $noIconsBelowSelect && null === $showIconTable) {
                    $configValue->value->items[] = new ArrayItem($this->createTrue(), new String_(
                        self::SHOW_ICON_TABLE
                    ));
                }
            }
        }

        return $node;
    }
}
