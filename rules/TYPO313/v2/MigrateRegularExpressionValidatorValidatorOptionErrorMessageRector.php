<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v2;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.2/Deprecation-102326-RegularExpressionValidatorValidatorOptionErrorMessage.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateRegularExpressionValidatorValidatorOptionErrorMessageRector\MigrateRegularExpressionValidatorValidatorOptionErrorMessageRectorTest
 */
final class MigrateRegularExpressionValidatorValidatorOptionErrorMessageRector extends AbstractRector implements DocumentedRuleInterface, MinPhpVersionInterface
{
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('RegularExpressionValidator validator option \"errorMessage\"', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Annotation as Extbase;

#[Extbase\Validate([
    'validator' => 'RegularExpression',
    'options' => [
        'regularExpression' => '/^simple[0-9]expression$/',
        'errorMessage' => 'Error message or LLL schema string',
    ],
])]
protected string $myProperty = '';
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Annotation as Extbase;

#[Extbase\Validate([
    'validator' => 'RegularExpression',
    'options' => [
        'regularExpression' => '/^simple[0-9]expression$/',
        'message' => 'Error message or LLL schema string'
    ],
])]
protected string $myProperty = '';
CODE_SAMPLE
        )]);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Attribute::class];
    }

    /**
     * @param Attribute $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $arrayItem = $this->findErrorMessage($node);
        if (! $arrayItem instanceof ArrayItem) {
            return null;
        }

        $arrayItem->key = new String_('message');

        return $node;
    }

    private function shouldSkip(Attribute $attribute): bool
    {
        if (! $this->isName($attribute, 'TYPO3\CMS\Extbase\Annotation\Validate')) {
            return true;
        }

        return ! $this->hasErrorMessageOption($attribute);
    }

    private function hasErrorMessageOption(Attribute $attribute): bool
    {
        $args = $attribute->args;

        $isRegularExpression = false;
        $hasErrorMessageOption = false;
        foreach ($args as $arg) {
            /** @var Array_ $argumentArray */
            $argumentArray = $arg->value;

            $items = $argumentArray->items;

            foreach ($items as $item) {
                if ($isRegularExpression === false
                    && $this->valueResolver->isValue($item->key, 'validator')
                    && $this->valueResolver->isValue($item->value, 'RegularExpression')
                ) {
                    $isRegularExpression = true;
                    continue;
                }

                if ($this->valueResolver->isValue($item->key, 'options')) {
                    /** @var Array_ $optionsArray */
                    $optionsArray = $item->value;
                    $optionItems = $optionsArray->items;

                    foreach ($optionItems as $optionItem) {
                        if ($this->valueResolver->isValue($optionItem->key, 'errorMessage')) {
                            $hasErrorMessageOption = true;
                        }
                    }
                }
            }
        }

        return $isRegularExpression && $hasErrorMessageOption;
    }

    private function findErrorMessage(Attribute $attribute): ?ArrayItem
    {
        $args = $attribute->args;

        foreach ($args as $arg) {
            /** @var Array_ $argumentArray */
            $argumentArray = $arg->value;

            $items = $argumentArray->items;

            foreach ($items as $item) {
                if ($this->valueResolver->isValue($item->key, 'options')) {
                    /** @var Array_ $optionsArray */
                    $optionsArray = $item->value;
                    $optionItems = $optionsArray->items;

                    foreach ($optionItems as $optionItem) {
                        if ($this->valueResolver->isValue($optionItem->key, 'errorMessage')) {
                            return $optionItem;
                        }
                    }
                }
            }
        }

        return null;
    }
}
