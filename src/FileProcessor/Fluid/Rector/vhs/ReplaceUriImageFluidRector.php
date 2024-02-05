<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs;

use Nette\Utils\Strings;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceUriImageFluidRector implements FluidRectorInterface
{
    /**
     * @var string
     */
    private const PATTERN = '#(v|vhs):uri.image#imsU';

    /**
     * @var string
     */
    private const REPLACEMENT = 'f:uri.image';

    public function transform(File $file): void
    {
        $content = $file->getFileContent();

        // TODO 1: handle 'relative' attribute -> transform to inverted 'absolute'
        // TODO 2: handle maxW attribute -> rename to maxWidth
        // TODO 3: handle maxH attribute -> rename to maxHeight
        $content = Strings::replace($content, self::PATTERN, self::REPLACEMENT);

        $file->changeFileContent($content);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use <f:uri.image> instead of <v:uri.image>', [
            new CodeSample(
                <<<'CODE_SAMPLE'
{v:uri.image(src:image.uid, treatIdAsReference: 1)}
{v:uri.image(src:image.uid, treatIdAsReference: 1, relative: 1)}
{v:uri.image(src:image.uid, treatIdAsReference: 1, relative: 0)}
{v:uri.image(src:image.uid, treatIdAsReference: 1, maxW: 250, maxH: 250)}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
{f:uri.image(src:image.uid, treatIdAsReference: 1)}
{f:uri.image(src:image.uid, treatIdAsReference: 1)}
{f:uri.image(src:image.uid, treatIdAsReference: 1, absolute: 1)}
{f:uri.image(src:image.uid, treatIdAsReference: 1, maxWidth: 250, maxHeight: 250)}
CODE_SAMPLE
            ),
        ]);
    }
}
