<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-79770-DeprecateInlineLocalizationMode.html
 */
final class RemoveLocalizationModeKeepIfNeededRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    private const LOCALIZATION_MODE = 'localizationMode';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove localizationMode keep if allowLanguageSynchronization is enabled', [
            new CodeSample(<<<'CODE_SAMPLE'
return [
    'columns' => [
        'foo' => [
            'label' => 'Bar',
            'config' => [
                'type' => 'inline',
                'appearance' => [
                    'behaviour' => [
                        'localizationMode' => 'keep',
                        'allowLanguageSynchronization' => true,
                    ],
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
return [
    'columns' => [
        'foo' => [
            'label' => 'Bar',
            'config' => [
                'type' => 'inline',
                'appearance' => [
                    'behaviour' => [
                        'allowLanguageSynchronization' => true,
                    ],
                ],
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

            $fieldName = $this->valueResolver->getValue($columnItem->key);

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
                    if (! $this->valueResolver->isValue($configItemValue->key, 'behaviour')) {
                        continue;
                    }

                    if (! $configItemValue->value instanceof Array_) {
                        continue;
                    }

                    if (! $this->isLocalizationModeKeepAndAllowLanguageSynchronization($configItemValue->value)) {
                        continue;
                    }

                    foreach ($configItemValue->value->items as $behaviourConfigurationItem) {
                        if (! $behaviourConfigurationItem instanceof ArrayItem) {
                            continue;
                        }

                        if (null === $behaviourConfigurationItem->key) {
                            continue;
                        }

                        if ($this->valueResolver->isValue($behaviourConfigurationItem->key, self::LOCALIZATION_MODE)) {
                            $this->removeNode($behaviourConfigurationItem);
                            $hasAstBeenChanged = true;
                            break;
                        }
                    }
                }
            }
        }

        return $hasAstBeenChanged ? $node : null;
    }

    private function isLocalizationModeKeepAndAllowLanguageSynchronization(Array_ $behaviourConfiguration): bool
    {
        $localizationMode = null;
        $allowLanguageSynchronization = null;

        foreach ($behaviourConfiguration->items as $behaviourConfigurationItem) {
            if (! $behaviourConfigurationItem instanceof ArrayItem) {
                continue;
            }

            if (null === $behaviourConfigurationItem->key) {
                continue;
            }

            if (! $this->valueResolver->isValues(
                $behaviourConfigurationItem->key,
                [self::LOCALIZATION_MODE, 'allowLanguageSynchronization']
            )) {
                continue;
            }

            $behaviourConfigurationValue = $this->valueResolver->getValue($behaviourConfigurationItem->value);

            if ($this->valueResolver->isValue($behaviourConfigurationItem->key, self::LOCALIZATION_MODE)) {
                $localizationMode = $behaviourConfigurationValue;
            } else {
                $allowLanguageSynchronization = $behaviourConfigurationValue;
            }
        }

        return $allowLanguageSynchronization && 'keep' === $localizationMode;
    }
}
