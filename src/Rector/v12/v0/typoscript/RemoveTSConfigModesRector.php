<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typoscript;

use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Collector\RemoveTypoScriptStatementCollector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AbstractTypoScriptRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-98437-WorkspaceTSConfigSwapModeAndChangeStageModeRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typoscript\RemoveTSConfigModesRector\RemoveTSConfigModesRectorTest
 */
final class RemoveTSConfigModesRector extends AbstractTypoScriptRector
{
    /**
     * @readonly
     */
    private RemoveTypoScriptStatementCollector $removeTypoScriptStatementCollector;

    /**
     * @readonly
     */
    private CurrentFileProvider $currentFileProvider;

    public function __construct(
        RemoveTypoScriptStatementCollector $removeTypoScriptStatementCollector,
        CurrentFileProvider $currentFileProvider
    ) {
        $this->removeTypoScriptStatementCollector = $removeTypoScriptStatementCollector;
        $this->currentFileProvider = $currentFileProvider;
    }

    public function enterNode(Statement $statement): void
    {
        if (! $statement instanceof Assignment) {
            return;
        }

        if ('options.workspaces.swapMode' !== $statement->object->absoluteName
         && 'options.workspaces.changeStageMode' !== $statement->object->absoluteName) {
            return;
        }

        $file = $this->currentFileProvider->getFile();

        if (! $file instanceof File) {
            return;
        }

        $this->removeTypoScriptStatementCollector->removeStatement($statement, $file);
        $this->hasChanged = true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove TSConfig options.workspaces.swapMode and options.workspaces.changeStageMode',
            [
            new CodeSample(
                <<<'CODE_SAMPLE'
options.workspaces.swapMode = any
options.workspaces.changeStageMode = any
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
-
CODE_SAMPLE
            ),
        
        ]);
    }
}
