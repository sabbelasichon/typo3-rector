<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79341-TCARichtextConfigurationInDefaultExtrasDropped.html
 */
final class RichtextFromDefaultExtrasToEnableRichtextRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('TCA richtext configuration in defaultExtras dropped', [
            new CodeSample(<<<'PHP'
[
    'columns' => [
        'content' => [
            'config' => [
                'type' => 'text',
            ],
            'defaultExtras' => 'richtext:rte_transform',
        ],
    ],
];
PHP
                , <<<'PHP'
[
    'columns' => [
        'content' => [
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
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

        $this->refactorRichtextColumns($columnItems);

        $types = $this->extractTypes($node);
        if (! $types instanceof ArrayItem) {
            return $node;
        }

        $typesItems = $types->value;

        if (! $typesItems instanceof Array_) {
            return $node;
        }

        foreach ($typesItems->items as $typesItem) {
            if (! $typesItem instanceof ArrayItem) {
                continue;
            }

            if (null === $typesItem->key) {
                continue;
            }

            if (! $typesItem->value instanceof Array_) {
                continue;
            }

            foreach ($typesItem->value->items as $configValue) {
                if (null === $configValue) {
                    continue;
                }

                if (null === $configValue->key) {
                    continue;
                }

                if (! $this->isValue($configValue->key, 'columnsOverrides')) {
                    continue;
                }

                if (! $configValue->value instanceof Array_) {
                    continue;
                }

                $this->refactorRichtextColumns($configValue->value);
            }
        }

        return $node;
    }

    private function isRichtextInDefaultExtras(ArrayItem $configValue): bool
    {
        if (null === $configValue->key) {
            return false;
        }

        if (! $this->isValue($configValue->key, 'defaultExtras')) {
            return false;
        }

        $defaultExtras = $this->getValue($configValue->value);

        if (! is_string($defaultExtras)) {
            return false;
        }

        return Strings::startsWith($defaultExtras, 'richtext');
    }

    private function refactorRichtextColumns(Array_ $columnItems): void
    {
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

            $hasRichTextConfiguration = false;
            foreach ($columnItem->value->items as $configValue) {
                if (null === $configValue) {
                    continue;
                }

                if (! $this->isRichtextInDefaultExtras($configValue)) {
                    continue;
                }

                $hasRichTextConfiguration = true;
                $this->removeNode($configValue);
            }

            if ($hasRichTextConfiguration) {
                $configurationArray = null;

                foreach ($columnItem->value->items as $configValue) {
                    if (null === $configValue) {
                        continue;
                    }

                    if (null === $configValue->key) {
                        continue;
                    }

                    if (! $this->isValue($configValue->key, 'config')) {
                        continue;
                    }

                    if (! $configValue->value instanceof Array_) {
                        continue;
                    }

                    $configurationArray = $configValue;
                }

                if (null === $configurationArray) {
                    $configurationArray = new ArrayItem(new Array_(), new String_('config'));
                    $columnItem->value->items[] = $configurationArray;
                }

                if ($configurationArray instanceof ArrayItem && $configurationArray->value instanceof Array_) {
                    $configurationArray->value->items[] = new ArrayItem($this->createTrue(), new String_(
                        'enableRichtext'
                    ));
                    $configurationArray->value->items[] = new ArrayItem(new String_('default'), new String_(
                        'richtextConfiguration'
                    ));
                }
            }
        }
    }
}
