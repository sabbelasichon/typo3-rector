<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.4/Breaking-77630-RemoveWizardIcons.html
 */
final class SubstituteOldWizardIconsRector extends AbstractRector implements ConfigurableRectorInterface
{
    use TcaHelperTrait;

    /**
     * @var string
     */
    public const OLD_TO_NEW_FILE_LOCATIONS = 'old_to_new_file_locations';

    /**
     * @var string[]
     */
    private $oldToNewFileLocations = [];

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'The TCA migration migrates the icon calls to the new output if used as wizard icon',
            [new CodeSample(<<<'PHP'
return [
    'ctrl' => [
    ],
    'columns' => [
        'bodytext' => [
            'config' => [
                'type' => 'text',
                'wizards' => [
                    't3editorHtml' => [
                        'icon' => 'wizard_table.gif',
                    ],
                ],
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
        'bodytext' => [
            'config' => [
                'type' => 'text',
                'wizards' => [
                    't3editorHtml' => [
                        'icon' => 'content-table',
                    ],
                ],
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

        $items = $columns->value;

        if (! $items instanceof Array_) {
            return null;
        }

        $oldFileNames = array_keys($this->oldToNewFileLocations);

        $hasAstBeenChanged = false;
        foreach ($items->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (null === $fieldValue->key) {
                continue;
            }

            $fieldName = $this->valueResolver->getValue($fieldValue->key);

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

                foreach ($configValue->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (null === $configItemValue->key) {
                        continue;
                    }

                    if (! $this->valueResolver->isValue($configItemValue->key, 'wizards')) {
                        continue;
                    }

                    if (! $configItemValue->value instanceof Array_) {
                        continue;
                    }

                    foreach ($configItemValue->value->items as $wizardItemValue) {
                        if (null === $wizardItemValue) {
                            continue;
                        }

                        if (! $wizardItemValue->value instanceof Array_) {
                            continue;
                        }

                        if (null === $wizardItemValue->key) {
                            continue;
                        }

                        foreach ($wizardItemValue->value->items as $wizardItemSubValue) {
                            if (! $wizardItemSubValue instanceof ArrayItem) {
                                continue;
                            }

                            if (null === $wizardItemSubValue->key) {
                                continue;
                            }

                            if ($this->valueResolver->isValue(
                                $wizardItemSubValue->key,
                                'icon'
                            ) && $this->valueResolver->isValues($wizardItemSubValue->value, $oldFileNames)) {
                                $wizardItemSubValue->value = new String_(
                                    $this->oldToNewFileLocations[$this->valueResolver->getValue(
                                    $wizardItemSubValue->value
                                )]
                                );
                                $hasAstBeenChanged = true;
                            }
                        }
                    }
                }
            }
        }

        if ($hasAstBeenChanged) {
            return $node;
        }
        return null;
    }

    public function configure(array $configuration): void
    {
        $this->oldToNewFileLocations = $configuration[self::OLD_TO_NEW_FILE_LOCATIONS] ?? [];
    }
}
