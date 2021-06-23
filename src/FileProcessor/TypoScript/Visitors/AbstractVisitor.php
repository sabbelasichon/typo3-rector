<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Visitors;

use Helmich\TypoScriptParser\Parser\AST\Statement;
use Helmich\TypoScriptParser\Parser\Traverser\Visitor;
use Rector\Core\Contract\Rector\RectorInterface;

abstract class AbstractVisitor implements Visitor, RectorInterface
{
    protected bool $hasChanged = false;

    protected ?Statement $originalStatement = null;

    protected ?Statement $newStatement = null;

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

    public function getOriginalStatement(): ?Statement
    {
        return $this->originalStatement;
    }

    public function getNewStatement(): ?Statement
    {
        return $this->newStatement;
    }

    public function reset(): void
    {
        $this->newStatement = null;
        $this->originalStatement = null;
    }
}
