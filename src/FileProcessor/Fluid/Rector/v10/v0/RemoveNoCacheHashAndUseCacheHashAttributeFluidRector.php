<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\v10\v0;

use Nette\Utils\Strings;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Deprecation-88406-SetCacheHashnoCacheHashOptionsInViewHelpersAndUriBuilder.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Fluid\Rector\v10\v0\RemoveNoCacheHashAndUseCacheHashAttributeFluidRector\RemoveNoCacheHashAndUseCacheHashAttributeFluidRectorTest
 */
final class RemoveNoCacheHashAndUseCacheHashAttributeFluidRector implements FluidRectorInterface
{
    public function transform(File $file): void
    {
        $content = $file->getFileContent();

        $content = Strings::replace($content, '# noCacheHash="(1|0|true|false)"#imsU', '');
        $content = Strings::replace($content, '# useCacheHash="(1|0|true|false)"#imsU', '');

        $file->changeFileContent($content);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove noCacheHash="1" and useCacheHash="1" attribute', [
            new CodeSample(
                <<<'CODE_SAMPLE'
<f:link.page noCacheHash="1">Link</f:link.page>
<f:link.typolink useCacheHash="1">Link</f:link.typolink>
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
<f:link.page>Link</f:link.page>
<f:link.typolink>Link</f:link.typolink>
CODE_SAMPLE
            ),
        ]);
    }
}
