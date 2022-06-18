<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v12\v0;

use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Collector\RemoveTypoScriptStatementCollector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AbstractTypoScriptRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97550-TypoScriptOptionConfigdisableCharsetHeaderRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\TypoScript\Rector\v12\v0\RemoveDisableCharsetHeaderConfigTypoScriptRector\RemoveDisableCharsetHeaderConfigTypoScriptRectorTest
 */
final class RemoveDisableCharsetHeaderConfigTypoScriptRector extends AbstractTypoScriptRector
{
    public function __construct(
        private readonly RemoveTypoScriptStatementCollector $removeTypoScriptStatementCollector,
        private readonly CurrentFileProvider $currentFileProvider
    ) {
    }

    public function enterNode(Statement $statement): void
    {
        if (! $statement instanceof Assignment) {
            return;
        }

        if ('config.disableCharsetHeader' !== $statement->object->absoluteName) {
            return;
        }

        $file = $this->currentFileProvider->getFile();

        if (! $file instanceof File) {
            return;
        }

        $this->removeTypoScriptStatementCollector->removeStatement($statement, $file);
        $this->hasChanged = true;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove config.disableCharsetHeader', [
            new CodeSample(
                <<<'CODE_SAMPLE'
config.disableCharsetHeader = true
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
-
CODE_SAMPLE
            ),
        ]);
    }
}
