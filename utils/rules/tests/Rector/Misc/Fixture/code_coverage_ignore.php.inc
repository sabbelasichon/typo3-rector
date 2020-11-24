<?php

use PhpParser\Node;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\RectorDefinition;

final class AddToMissingCodeCoverageIgnore extends AbstractRector implements PhpRectorInterface
{
    public function getNodeTypes(): array
    {
    }

    public function refactor(Node $node): ?Node
    {
    }

    public function getDefinition(): RectorDefinition
    {
    }
}

final class HasCodeCoverageIgnore extends AbstractRector implements PhpRectorInterface
{
    public function getNodeTypes(): array
    {
    }

    public function refactor(Node $node): ?Node
    {
    }

    /**
     * @codeCoverageIgnore
     * @return RectorDefinition
     */
    public function getDefinition(): RectorDefinition
    {
    }
}

?>
-----
<?php

use PhpParser\Node;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\RectorDefinition;

final class AddToMissingCodeCoverageIgnore extends AbstractRector implements PhpRectorInterface
{
    public function getNodeTypes(): array
    {
    }

    public function refactor(Node $node): ?Node
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
    }
}

final class HasCodeCoverageIgnore extends AbstractRector implements PhpRectorInterface
{
    public function getNodeTypes(): array
    {
    }

    public function refactor(Node $node): ?Node
    {
    }

    /**
     * @codeCoverageIgnore
     * @return RectorDefinition
     */
    public function getDefinition(): RectorDefinition
    {
    }
}

?>
