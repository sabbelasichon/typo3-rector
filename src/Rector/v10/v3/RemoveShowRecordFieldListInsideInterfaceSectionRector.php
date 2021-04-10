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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.3/Feature-88901-RenderAllFieldsInElementInformationController.html?highlight=showrecordfieldlist
 */
final class RemoveShowRecordFieldListInsideInterfaceSectionRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove showRecordFieldList inside section interface', [
            new CodeSample(<<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'interface' => [
        'showRecordFieldList' => 'foo,bar,baz',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
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

        $interface = $this->extractInterface($node);

        if (! $interface instanceof ArrayItem) {
            return null;
        }

        $interfaceItems = $interface->value;

        if (! $interfaceItems instanceof Array_) {
            $this->removeNode($interface);
            return null;
        }

        $remainingInterfaceItems = count($interfaceItems->items);

        foreach ($interfaceItems->items as $interfaceItem) {
            if (! $interfaceItem instanceof ArrayItem) {
                continue;
            }

            if (null === $interfaceItem->key) {
                continue;
            }

            if ($this->valueResolver->isValue($interfaceItem->key, 'showRecordFieldList')) {
                $this->removeNode($interfaceItem);
                --$remainingInterfaceItems;
                break;
            }
        }

        if (0 === $remainingInterfaceItems) {
            $this->removeNode($interface);
            return $node;
        }

        return null;
    }
}
