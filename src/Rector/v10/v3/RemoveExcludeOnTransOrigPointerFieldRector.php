<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.3/Important-89672-TransOrigPointerFieldIsNotLongerAllowedToBeExcluded.html
 */
final class RemoveExcludeOnTransOrigPointerFieldRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('transOrigPointerField is not longer allowed to be excluded', [
            new CodeSample(<<<'PHP'
return [
    'ctrl' => [
        'transOrigPointerField' => 'l10n_parent',
    ],
    'columns' => [
        'l10n_parent' => [
            'exclude' => true,
            'config' => [
                'type' => 'select',
            ],
        ],
    ],
];
PHP
                , <<<'PHP'
return [
    'ctrl' => [
        'transOrigPointerField' => 'l10n_parent',
    ],
    'columns' => [
        'l10n_parent' => [
            'config' => [
                'type' => 'select',
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

        $ctrl = $this->extractCtrl($node);

        if (! $ctrl instanceof ArrayItem) {
            return null;
        }

        $ctrlItems = $ctrl->value;

        if (! $ctrlItems instanceof Array_) {
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

        $transOrigPointerField = null;
        foreach ($ctrlItems->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (null === $fieldValue->key) {
                continue;
            }

            if ($this->isValue($fieldValue->key, 'transOrigPointerField')) {
                $transOrigPointerField = $this->getValue($fieldValue->value);
                break;
            }
        }

        if (null === $transOrigPointerField) {
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

            if ($fieldName !== $transOrigPointerField) {
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

                $configFieldName = $this->getValue($configValue->key);

                if ('exclude' === $configFieldName) {
                    $this->removeNode($configValue);
                }
            }
        }

        return $node;
    }
}
