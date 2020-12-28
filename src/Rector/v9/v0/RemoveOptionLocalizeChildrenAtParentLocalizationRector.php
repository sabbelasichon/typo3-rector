<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Breaking-82709-TCAOptionLocalizeChildrenAtParentLocalizationRemoved.html
 */
final class RemoveOptionLocalizeChildrenAtParentLocalizationRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove option localizeChildrenAtParentLocalization', [new CodeSample(<<<'PHP'
return [
    'ctrl' => [],
    'columns' => [
        'foo' => [
            'config' =>
                [
                    'type' => 'inline',
                    'behaviour' => [
                        'localizeChildrenAtParentLocalization' => '1',
                    ],
                ],
        ],
    ],
];
PHP
                , <<<'PHP'
return [
    'ctrl' => [],
    'columns' => [
        'foo' => [
            'config' =>
                [
                    'type' => 'inline',
                    'behaviour' => [],
                ],
        ],
    ],
];
PHP
            )]);
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

            $fieldName = $this->getValue($columnItem->key);

            if (null === $fieldName) {
                continue;
            }

            if (! $columnItem->value instanceof Array_) {
                continue;
            }

            foreach ($columnItem->value->items as $columnItemConfiguration) {
                if (null === $columnItemConfiguration) {
                    continue;
                }

                if (! $columnItemConfiguration->value instanceof Array_) {
                    continue;
                }

                if (! $this->isInlineType($columnItemConfiguration->value)) {
                    continue;
                }

                foreach ($columnItemConfiguration->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (null === $configItemValue->key) {
                        continue;
                    }
                    if (! $this->isValue($configItemValue->key, 'behaviour')) {
                        continue;
                    }

                    if (! $configItemValue->value instanceof Array_) {
                        continue;
                    }

                    foreach ($configItemValue->value->items as $behaviourConfigurationItem) {
                        if (null === $behaviourConfigurationItem) {
                            continue;
                        }

                        if (! $behaviourConfigurationItem instanceof ArrayItem) {
                            continue;
                        }

                        if (null === $behaviourConfigurationItem->key) {
                            continue;
                        }

                        if ($this->isValue($behaviourConfigurationItem->key, 'localizeChildrenAtParentLocalization')) {
                            $this->removeNode($behaviourConfigurationItem);
                        }
                    }
                }
            }
        }

        return $node;
    }
}
