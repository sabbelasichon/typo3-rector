<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\__Major__\__Minor__;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;

/**
 * @see __Rst_File__
 */
final class __Name__ extends AbstractRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('__Description__', [new CodeSample(<<<'PHP'
PHP
                , <<<'PHP'
PHP
            )]);
    }

    /**
     * @return string[]
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
}
