<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.4/Breaking-77592-DroppedTCAOptionShowIfRTEInTypecheck.html
 */
final class RemoveOptionShowIfRteRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Dropped TCA option showIfRTE in type=check', [new CodeSample(<<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
        'rte_enabled' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.disableRTE',
            'config' => [
                'type' => 'check',
                'showIfRTE' => 1
            ]
        ],
    ],
];
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
        'rte_enabled' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.disableRTE',
            'config' => [
                'type' => 'check',
            ]
        ],
    ],
];
CODE_SAMPLE
            )]);
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

                if (! $this->isRenderTypeCheck($configValue->value)) {
                    continue;
                }

                foreach ($configValue->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (null === $configItemValue->key) {
                        continue;
                    }

                    if ($this->valueResolver->isValue($configItemValue->key, 'showIfRTE')) {
                        $this->removeNode($configItemValue);
                        return $node;
                    }
                }
            }
        }

        return null;
    }

    private function isRenderTypeCheck(Array_ $configValue): bool
    {
        foreach ($configValue->items as $configItemValue) {
            if (! $configItemValue instanceof ArrayItem) {
                continue;
            }

            if (null === $configItemValue->key) {
                continue;
            }

            if ($this->valueResolver->isValue($configItemValue->key, 'type') && $this->valueResolver->isValue(
                $configItemValue->value,
                'check'
            )) {
                return true;
            }
        }

        return false;
    }
}
