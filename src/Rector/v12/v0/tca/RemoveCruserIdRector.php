<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\tca;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Rector\Tca\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-98024-TCA-option-cruserid-removed.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\RemoveCruserIdRector\RemoveCruserIdRectorTest
 */
final class RemoveCruserIdRector extends AbstractRector
{
    use TcaHelperTrait;

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

        $ctrl = $this->extractCtrl($node);

        if (! $ctrl instanceof ArrayItem) {
            return null;
        }

        $ctrlItems = $ctrl->value;

        if (! $ctrlItems instanceof Array_) {
            $this->removeNode($ctrl);
            return null;
        }

        $remainingInterfaceItems = count($ctrlItems->items);

        foreach ($ctrlItems->items as $ctrlItem) {
            if (! $ctrlItem instanceof ArrayItem) {
                continue;
            }

            if (! $ctrlItem->key instanceof Expr) {
                continue;
            }

            if ($this->valueResolver->isValue($ctrlItem->key, 'cruser_id')) {
                $this->removeNode($ctrlItem);
                --$remainingInterfaceItems;
                break;
            }
        }

        if ($remainingInterfaceItems === 0) {
            $this->removeNode($ctrl);
            return $node;
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove the TCA option cruser_id', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'label' => 'foo',
        'cruser_id' => 'cruser_id',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'label' => 'foo',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
        )]);
    }
}
