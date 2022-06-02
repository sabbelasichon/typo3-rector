<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\PostRector\v8\v7;

use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\TypoScriptPostRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.7/Breaking-80412-NewSharedContentElementTyposcriptLibraryObjectForFluidStyledContent.html
 */
final class LibFluidContentToContentElementTypoScriptPostRector implements TypoScriptPostRectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert lib.fluidContent to lib.contentElement', [
            new CodeSample(
                <<<'CODE_SAMPLE'
lib.fluidContent.templateRootPaths.200 = EXT:your_extension_key/Resources/Private/Templates/
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
lib.contentElement.templateRootPaths.200 = EXT:your_extension_key/Resources/Private/Templates/
CODE_SAMPLE
            ),
        ]);
    }

    public function apply(string $typoScriptContent): string
    {
        return str_replace('lib.fluidContent', 'lib.contentElement', $typoScriptContent);
    }
}
