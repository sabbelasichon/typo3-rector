<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Visitors;

use Helmich\TypoScriptParser\Parser\AST\FileIncludeStatement;
use Helmich\TypoScriptParser\Parser\AST\Statement;

final class FileIncludeToImportStatementVisitor extends AbstractVisitor
{
    public function enterNode(Statement $statement): void
    {
        if (! $statement instanceof FileIncludeStatement) {
            return;
        }

        if (null !== $statement->condition) {
            return;
        }

        if ($statement->newSyntax) {
            return;
        }

        $statement->newSyntax = true;
    }
}
