<?php

use PhpParser\Node;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddToMissingCodeCoverageIgnore extends AbstractRector implements PhpRectorInterface
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

final class HasCodeCoverageIgnore extends AbstractRector implements PhpRectorInterface
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

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
    }
}

?>
-----
<?php

use PhpParser\Node;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddToMissingCodeCoverageIgnore extends AbstractRector implements PhpRectorInterface
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
    }

    public function refactor(Node $node): ?Node
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
    }
}

final class HasCodeCoverageIgnore extends AbstractRector implements PhpRectorInterface
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
    }

    public function refactor(Node $node): ?Node
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
    }
}

?>
