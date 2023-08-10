<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v11\v0;

use Helmich\TypoScriptParser\Parser\AST\Operator\ObjectCreation;
use Helmich\TypoScriptParser\Parser\AST\Scalar;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AbstractTypoScriptRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Breaking-91562-CObjectTEMPLATERemoved.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\TypoScript\TypoScriptProcessorTest
 */
final class TemplateToFluidTemplateTypoScriptRector extends AbstractTypoScriptRector
{
    public function enterNode(Statement $statement): void
    {
        if (! $statement instanceof ObjectCreation) {
            return;
        }

        if (! $statement->value instanceof Scalar) {
            return;
        }

        if ($statement->value->value !== 'TEMPLATE') {
            return;
        }

        $statement->value->value = 'FLUIDTEMPLATE';
        $this->hasChanged = true;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert TEMPLATE to FLUIDTEMPLATE', [
            new CodeSample(
                <<<'CODE_SAMPLE'
page.10 = TEMPLATE
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
page.10 = FLUIDTEMPLATE
CODE_SAMPLE
            ),
        ]);
    }
}
