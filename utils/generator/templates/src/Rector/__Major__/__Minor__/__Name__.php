<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\__Major__\__Minor__;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see __Rst_File__
 */
final class __Name__ extends AbstractRector
{
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
        return [];
    }

    public function refactor(Node $node): ?Node
    {
        // change the node
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('__Description__', [new CodeSample(<<<'CODE_SAMPLE'
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
CODE_SAMPLE
        )]);
    }
}
