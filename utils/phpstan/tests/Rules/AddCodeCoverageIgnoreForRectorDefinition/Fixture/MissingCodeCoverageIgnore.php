<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddCodeCoverageIgnoreForRectorDefinition\Fixture;

use PhpParser\Node;
use Rector\Contract\Rector\RectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class MissingCodeCoverageIgnore extends AbstractRector implements RectorInterface
{
    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }
}
