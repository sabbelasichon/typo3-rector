<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\tca;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Rector\Tca\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97312-RemoveContextSensitiveHelp.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\RemoveTCAInterfaceAlwaysDescriptionRector\RemoveTCAInterfaceAlwaysDescriptionRectorTest
 */
final class RemoveTCAInterfaceAlwaysDescriptionRector extends AbstractRector
{
    use TcaHelperTrait;

    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
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
        if (! $this->isFullTca($node)) {
            return null;
        }

        $interface = $this->extractInterface($node);

        if (! $interface instanceof ArrayItem) {
            return null;
        }

        $interfaceItems = $interface->value;

        if (! $interfaceItems instanceof Array_) {
            if ($node->expr instanceof Array_) {
                $this->removeArrayItemFromArrayByKey($node->expr, 'interface');
            }

            return null;
        }

        $remainingInterfaceItems = count($interfaceItems->items);

        foreach ($interfaceItems->items as $interfaceItemKey => $interfaceItem) {
            if (! $interfaceItem instanceof ArrayItem) {
                continue;
            }

            if (! $interfaceItem->key instanceof Expr) {
                continue;
            }

            if ($this->valueResolver->isValue($interfaceItem->key, 'always_description')) {
                unset($interfaceItems->items[$interfaceItemKey]);
                --$remainingInterfaceItems;
                break;
            }
        }

        if ($remainingInterfaceItems === 0 && $node->expr instanceof Array_) {
            $this->removeArrayItemFromArrayByKey($node->expr, 'interface');
            return $node;
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition("Remove ['interface']['always_description']", [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'interface' => [
        'always_description' => 'foo,bar,baz',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
    ],
];
CODE_SAMPLE
        )]);
    }
}
