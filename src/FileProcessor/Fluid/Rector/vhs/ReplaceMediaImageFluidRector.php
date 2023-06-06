<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs;

use Nette\Utils\Strings;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceMediaImageFluidRector implements FluidRectorInterface
{
    /**
     * @var string
     */
    private const PATTERN = '#(v|vhs):media.image([\( ])#imsU';

    /**
     * @var string
     */
    private const REPLACEMENT = 'f:image$2';

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
        return new RuleDefinition('Use <f:image> instead of <v:media.image>', [
            new CodeSample(
                <<<'CODE_SAMPLE'
<v:media src="{image.uid}" treatIdAsReference="true" />
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
<f:image src="{image.uid}" treatIdAsReference="true" />
CODE_SAMPLE
            ),
        ]);
    }
}
