<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector;

use Helmich\TypoScriptParser\Parser\AST\FileIncludeStatement;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Feature-82812-NewSyntaxForImportingTypoScriptFiles.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\TypoScript\TypoScriptProcessorTest
 */
final class FileIncludeToImportStatementTypoScriptRector extends AbstractTypoScriptRector
{
    public function __construct(
        private CurrentFileProvider $currentFileProvider
    ) {
    }

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

        $file = $this->currentFileProvider->getFile();

        if ($file instanceof File) {
            $file->addRectorClassWithLine(new RectorWithLineChange($this, $statement->sourceLine));
        }

        $this->hasChanged = true;
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
