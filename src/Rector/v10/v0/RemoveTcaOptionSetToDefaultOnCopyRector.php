<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Breaking-87989-TCAOptionSetToDefaultOnCopyRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\RemoveTcaOptionSetToDefaultOnCopyRector\RemoveTcaOptionSetToDefaultOnCopyRectorTest
 */
final class RemoveTcaOptionSetToDefaultOnCopyRector extends AbstractRector
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

        $ctrlArrayItem = $this->extractCtrl($node);

        if (! $ctrlArrayItem instanceof ArrayItem) {
            return null;
        }

        $items = $ctrlArrayItem->value;

        if (! $items instanceof Array_) {
            return null;
        }

        $hasAstBeenChanged = false;
        if ($this->removeArrayItemFromArrayByKey($items, 'setToDefaultOnCopy')) {
            $hasAstBeenChanged = true;
        }

        return $hasAstBeenChanged ? $node : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('TCA option setToDefaultOnCopy removed', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'selicon_field' => 'icon',
        'setToDefaultOnCopy' => 'foo'
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'selicon_field' => 'icon'
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
        )]);
    }
}
