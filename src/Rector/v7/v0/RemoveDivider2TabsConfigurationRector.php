<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.0/Breaking-62833-Dividers2Tabs.html
 */
final class RemoveDivider2TabsConfigurationRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removed dividers2tabs functionality', [new CodeSample(<<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'dividers2tabs' => true,
        'label' => 'complete_identifier',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'label' => 'complete_identifier',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
            )]);
    }

    /**
<<<<<<< HEAD
     * @return array<class-string<\PhpParser\Node>>
     */

    /**
=======
>>>>>>> 8781ff4... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
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
        if (! $this->isTca($node)) {
            return null;
        }

        $ctrl = $this->extractCtrl($node);

        if (! $ctrl instanceof ArrayItem) {
            return null;
        }

        $ctrlItems = $ctrl->value;

        if (! $ctrlItems instanceof Array_) {
            return null;
        }

        foreach ($ctrlItems->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (null === $fieldValue->key) {
                continue;
            }

            if ($this->valueResolver->isValue($fieldValue->key, 'dividers2tabs')) {
                $this->removeNode($fieldValue);
                return $node;
            }
        }

        return null;
    }
}
