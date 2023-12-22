<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v4\typoscript;

use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AbstractTypoScriptRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.4/Deprecation-100461-TypoScriptOptionConfigxhtmlDoctype.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v4\typoscript\MigrateXhtmlDoctypeRector\MigrateXhtmlDoctypeRectorTest
 */
final class MigrateXhtmlDoctypeRector extends AbstractTypoScriptRector
{
    public function enterNode(Statement $statement): void
    {
        if (! $statement instanceof Assignment) {
            return;
        }

        if ($statement->object->absoluteName !== 'config.xhtmlDoctype') {
            return;
        }

        $statement->object->relativeName = str_replace('xhtmlDoctype', 'doctype',$statement->object->relativeName);
        $statement->object->absoluteName = str_replace('xhtmlDoctype', 'doctype',$statement->object->absoluteName);

        $this->hasChanged = true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate typoscript xhtmlDoctype to doctype', [new CodeSample(
            <<<'CODE_SAMPLE'
config.xhtmlDoctype = 1
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
config.doctype = 1
CODE_SAMPLE
        )]);
    }
}
