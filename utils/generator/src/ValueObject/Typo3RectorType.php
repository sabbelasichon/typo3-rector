<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Generator\Contract\Typo3RectorTypeInterface;

final class Typo3RectorType implements Typo3RectorTypeInterface
{
    public function __toString(): string
    {
        return 'typo3';
    }

    public function getRectorClass(): string
    {
        return AbstractRector::class;
    }

    public function getRectorShortClassName(): string
    {
        return 'AbstractRector';
    }

    public function getRectorBodyTemplate(): string
    {
        return <<<'EOF'
    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node::class];
    }

    /**
     * @param \PhpParser\Node $node
     */
    public function refactor(\PhpParser\Node $node): ?\PhpParser\Node
    {
        return $node;
    }
EOF;
    }
}
