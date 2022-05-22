<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector;

use Helmich\TypoScriptParser\Parser\AST\Statement;
use Helmich\TypoScriptParser\Parser\Traverser\Visitor;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\TypoScriptRectorInterface;

abstract class AbstractTypoScriptRector implements Visitor, TypoScriptRectorInterface
{
    protected bool $hasChanged = false;

    public function enterTree(array $statements): void
    {
    }

    public function enterNode(Statement $statement): void
    {
    }

    public function exitNode(Statement $statement): void
    {
    }

    public function exitTree(array $statements): void
    {
    }

    public function hasChanged(): bool
    {
        return $this->hasChanged;
    }
}
