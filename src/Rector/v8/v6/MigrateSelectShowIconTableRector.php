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

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79440-TcaChanges.html
 */
final class MigrateSelectShowIconTableRector extends AbstractRector
{
    use TcaHelperTrait;

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

        $columnItems = $columns->value;

        if (! $columnItems instanceof Array_) {
            return null;
        }

        $hasAstBeenChanged = false;
        foreach ($columnItems->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (null === $fieldValue->key) {
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

                if (! $this->isConfigType($configValue->value, 'select')) {
                    continue;
                }

                if (! $this->hasRenderType($configValue->value)) {
                    continue;
                }

                foreach ($configValue->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (null === $configItemValue->key) {
                        continue;
                    }

                    if (! $this->valueResolver->isValues($configItemValue->key, ['selicon_cols', 'showIconTable'])) {
                        continue;
                    }

                    if ($this->valueResolver->isValue(
                        $configItemValue->key,
                        'showIconTable'
                    ) && $this->valueResolver->isTrue($configItemValue->value)) {
                        $configValue->value->items[] = new ArrayItem($this->nodeFactory->createArray([
                            'selectIcons' => [
                                'disabled' => false,
                            ],
                        ]), new String_('fieldWizard'));
                    }

                    $this->removeNode($configItemValue);
                    $hasAstBeenChanged = true;
                }
            }
        }

        if ($hasAstBeenChanged) {
            return $node;
        }
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate select showIconTable', [
            new CodeSample(<<<'PHP'
return [
    'ctrl' => [
    ],
    'columns' => [
        'foo' => [
            'config' => [
                'type' => 'select',
                'items' => [
                    ['foo 1', 'foo1', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                    ['foo 2', 'foo2', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                ],
                'renderType' => 'selectSingle',
                'selicon_cols' => 16,
                'showIconTable' => true
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
        'foo' => [
            'config' => [
                'type' => 'select',
                'items' => [
                    ['foo 1', 'foo1', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                    ['foo 2', 'foo2', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                ],
                'renderType' => 'selectSingle',
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
    ],
];
PHP
            ),
        ]);
    }
}
