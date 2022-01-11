<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector;

use Helmich\TypoScriptParser\Parser\AST\NestedAssignment;
use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-fluid-styled-content/8.7/en-us/Configuration/OverridingFluidTemplates/
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\TypoScript\TypoScriptProcessorTest
 */
final class LibFluidContentToLibContentElementRector extends AbstractTypoScriptRector
{
    public function enterNode(Statement $statement): void
    {
        if (! is_a($statement, NestedAssignment::class) && ! is_a($statement, Assignment::class)) {
            return;
        }

        if ('lib.fluidContent' === $statement->object->relativeName) {
            $this->hasChanged = true;
            $statement->object->relativeName = 'lib.contentElement';

            return;
        }

        if ('fluidContent' === $statement->object->relativeName) {
            $this->hasChanged = true;
            $statement->object->relativeName = 'contentElement';
        }
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert lib.fluidContent to lib.contentElement', [
            new CodeSample(
                <<<'CODE_SAMPLE'
lib.fluidContent {
   templateRootPaths {
      200 = EXT:your_extension_key/Resources/Private/Templates/
   }
   partialRootPaths {
      200 = EXT:your_extension_key/Resources/Private/Partials/
   }
   layoutRootPaths {
      200 = EXT:your_extension_key/Resources/Private/Layouts/
   }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
lib.contentElement {
   templateRootPaths {
      200 = EXT:your_extension_key/Resources/Private/Templates/
   }
   partialRootPaths {
      200 = EXT:your_extension_key/Resources/Private/Partials/
   }
   layoutRootPaths {
      200 = EXT:your_extension_key/Resources/Private/Layouts/
   }
}
CODE_SAMPLE
            ),
        ]);
    }
}
