<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v7\v1;

use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AbstractTypoScriptRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/7.1/Feature-56236-Multiple-HTTP-Headers-In-Frontend.html
 */
final class AdditionalHeadersToArrayTypoScriptRector extends AbstractTypoScriptRector
{
    public function enterNode(Statement $statement): void
    {
        if (! $statement instanceof Assignment) {
            return;
        }

        if (! str_ends_with($statement->object->relativeName, 'additionalHeaders')) {
            return;
        }

        $this->hasChanged = true;

        $statement->object->relativeName = 'additionalHeaders.10.header';
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use array syntax for additionalHeaders', [
            new CodeSample(
                <<<'CODE_SAMPLE'
config.additionalHeaders = Content-type:application/json
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
config.additionalHeaders.10.header = Content-type:application/json
CODE_SAMPLE
            ),
        ]);
    }
}
