<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v8\v7;

use Helmich\TypoScriptParser\Parser\AST\NestedAssignment;
use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AbstractTypoScriptRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.7/Breaking-80412-NewSharedContentElementTyposcriptLibraryObjectForFluidStyledContent.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\TypoScript\TypoScriptProcessorTest
 */
final class LibFluidContentToLibContentElementRector extends AbstractTypoScriptRector
{
    public function enterNode(Statement $statement): void
    {
        if (! $statement instanceof NestedAssignment && ! $statement instanceof Assignment) {
            return;
        }

        if ($statement->object->relativeName === 'lib.fluidContent') {
            $this->hasChanged = true;
            $statement->object->relativeName = 'lib.contentElement';

            return;
        }

        if ($statement->object->relativeName === 'fluidContent') {
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
