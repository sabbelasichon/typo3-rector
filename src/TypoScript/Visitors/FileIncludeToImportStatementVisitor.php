<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Visitors;

use Helmich\TypoScriptParser\Parser\AST\FileIncludeStatement;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

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

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert old include statement to new import syntax', [
            new CodeSample(
                <<<'CODE_SAMPLE'
<INCLUDE_TYPOSCRIPT: source="FILE:conditions.typoscript">
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
@import conditions.typoscript
CODE_SAMPLE
            ),
        ]);
    }
}
