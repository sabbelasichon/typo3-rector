<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v3;

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
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.3/Breaking-77081-RemovedTCASelectTreeOptions.html
 */
final class RemovedTcaSelectTreeOptionsRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removed TCA tree options: width, allowRecursiveMode, autoSizeMax', [
            new CodeSample(<<<'PHP'
return [
    'ctrl' => [
    ],
    'columns' => [
        'categories' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'selectTree',
                'autoSizeMax' => 5,
                'treeConfig' => [
                    'appearance' => [
                        'width' => 100,
                        'allowRecursiveMode' => true
                    ]
                ]
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
        'categories' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'selectTree',
                'size' => 5,
                'treeConfig' => [
                    'appearance' => []
                ]
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

        $columnItems = $columns->value;

        if (! $columnItems instanceof Array_) {
            return null;
        }

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

                if (! $this->isRenderTypeSelectTree($configValue->value)) {
                    continue;
                }

                foreach ($configValue->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (null === $configItemValue->key) {
                        continue;
                    }

                    if ($this->isValue($configItemValue->key, 'autoSizeMax')) {
                        $configItemValue->key = new String_('size');
                    } elseif ($configItemValue->value instanceof Array_ && $this->isValue(
                            $configItemValue->key,
                            'treeConfig'
                        )) {
                        foreach ($configItemValue->value->items as $treeConfigValue) {
                            if (! $treeConfigValue instanceof ArrayItem) {
                                continue;
                            }

                            if (null === $treeConfigValue->key) {
                                continue;
                            }

                            if (! $this->isValue($treeConfigValue->key, 'appearance')) {
                                continue;
                            }

                            if (! $treeConfigValue->value instanceof Array_) {
                                continue;
                            }

                            foreach ($treeConfigValue->value->items as $appearanceConfigValue) {
                                if (! $appearanceConfigValue instanceof ArrayItem) {
                                    continue;
                                }

                                if (null === $appearanceConfigValue->key) {
                                    continue;
                                }

                                if (! $this->isValues($appearanceConfigValue->key, ['width', 'allowRecursiveMode'])) {
                                    continue;
                                }

                                $this->removeNode($appearanceConfigValue);
                            }
                        }
                    }
                }
            }
        }

        return $node;
    }

    private function isRenderTypeSelectTree(Array_ $configValue): bool
    {
        foreach ($configValue->items as $configItemValue) {
            if (! $configItemValue instanceof ArrayItem) {
                continue;
            }

            if (null === $configItemValue->key) {
                continue;
            }

            if ($this->isValue($configItemValue->key, 'renderType') && $this->isValue(
                    $configItemValue->value,
                    'selectTree'
                )) {
                return true;
            }
        }

        return false;
    }
}
