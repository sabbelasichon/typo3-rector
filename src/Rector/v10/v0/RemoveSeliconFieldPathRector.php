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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Breaking-87937-TCAOption_selicon_field_path_removed.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\RemoveSeliconFieldPathRector\RemoveSeliconFieldPathRectorTest
 */
final class RemoveSeliconFieldPathRector extends AbstractRector
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
        if ($this->removeArrayItemFromArrayByKey($items, 'selicon_field_path')) {
            $hasAstBeenChanged = true;
        }

        return $hasAstBeenChanged ? $node : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('TCA option "selicon_field_path" removed', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'selicon_field' => 'icon',
        'selicon_field_path' => 'uploads/media'
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'selicon_field' => 'icon',
    ],
];
CODE_SAMPLE
        )]);
    }
}
