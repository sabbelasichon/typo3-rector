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
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79341-TCARichtextConfigurationInDefaultExtrasDropped.html
 */
final class RichtextFromDefaultExtrasToEnableRichtextRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var bool
     */
    private $hasAstBeenChanged = false;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('TCA richtext configuration in defaultExtras dropped', [
            new CodeSample(<<<'CODE_SAMPLE'
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
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
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
CODE_SAMPLE
            ),
        ]);
    }

    /**
<<<<<<< HEAD
     * @return array<class-string<Node>>
=======
     * @return array<class-string<\PhpParser\Node>>
>>>>>>> f7cbd4b... make PHPStan smarted on getNodeTypes()
     */

    /**
     * @return array<class-string<\PhpParser\Node>>
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
        $this->hasAstBeenChanged = false;
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
            return $this->hasAstBeenChanged ? $node : null;
        }

        $typesItems = $types->value;

        if (! $typesItems instanceof Array_) {
            return $this->hasAstBeenChanged ? $node : null;
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

                if (! $this->valueResolver->isValue($configValue->key, 'columnsOverrides')) {
                    continue;
                }

                if (! $configValue->value instanceof Array_) {
                    continue;
                }

                $this->refactorRichtextColumns($configValue->value);
            }
        }

        return $this->hasAstBeenChanged ? $node : null;
    }

    private function isRichtextInDefaultExtras(ArrayItem $configValue): bool
    {
        if (null === $configValue->key) {
            return false;
        }

        if (! $this->valueResolver->isValue($configValue->key, 'defaultExtras')) {
            return false;
        }

        $defaultExtras = $this->valueResolver->getValue($configValue->value);

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
                $this->hasAstBeenChanged = true;
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

                    if (! $this->valueResolver->isValue($configValue->key, 'config')) {
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
                    $this->hasAstBeenChanged = true;
                }

                if ($configurationArray instanceof ArrayItem && $configurationArray->value instanceof Array_) {
                    $configurationArray->value->items[] = new ArrayItem($this->nodeFactory->createTrue(), new String_(
                        'enableRichtext'
                    ));
                    $configurationArray->value->items[] = new ArrayItem(new String_('default'), new String_(
                        'richtextConfiguration'
                    ));
                    $this->hasAstBeenChanged = true;
                }
            }
        }
    }
}
