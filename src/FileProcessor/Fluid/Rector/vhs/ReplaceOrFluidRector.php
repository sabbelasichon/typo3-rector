<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs;

use Nette\Utils\Strings;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceOrFluidRector implements FluidRectorInterface
{
    public function transform(File $file): void
    {
        $content = $file->getFileContent();

        $content = Strings::replace($content, '#-> (v|vhs):or\(alternative:(\s?)([^),]+)\)#ims', '?: $3');

        $content = Strings::replace(
            $content,
            '#(v|vhs):or\(content:(\s?)([^),]+),(\s?)alternative:(\s?)([^),]+)\)#ims',
            '$3 ?: $6'
        );

        $file->changeFileContent($content);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use <f:or> instead of <v:or>', [
            new CodeSample(
                <<<'CODE_SAMPLE'
{someVariable -> v:or(alternative: 'Fallback text')}
{v:or(content: someVariable, alternative: 'Fallback text')}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
{someVariable ?: 'Fallback text'}
{someVariable ?: 'Fallback text'}
CODE_SAMPLE
            ),
        ]);
    }
}
