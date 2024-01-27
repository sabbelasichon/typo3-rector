<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddChangelogDocBlockForRectorClass\Fixture;

use PhpParser\Node;
use Rector\Contract\Rector\RectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/sabbelasichon/typo3-rector/issues/2169
 */
final class SkipWithChangelog extends AbstractRector implements RectorInterface
{
    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
    }

    public function refactor(Node $node): ?Node
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
    }
}
