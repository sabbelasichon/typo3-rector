<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs;

use Nette\Utils\Strings;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceLFluidRector implements FluidRectorInterface
{
    /**
     * @var string
     */
    private const PATTERN = '#(v|vhs):l([\( ])#imsU';

    /**
     * @var string
     */
    private const REPLACEMENT = 'f:translate$2';

    public function transform(File $file): void
    {
        $content = $file->getFileContent();

        $content = Strings::replace($content, self::PATTERN, self::REPLACEMENT);

        $file->changeFileContent($content);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use <f:translate> instead of <v:l>', [
            new CodeSample(
                <<<'CODE_SAMPLE'
<v:l key="my-key" extensionName="my_extension" />
<vhs:l key="my-other-key" />
<v:loop ...>
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
<f:translate key="my-key" extensionName="my_extension" />
<f:translate key="my-other-key" />
<v:loop ...>
CODE_SAMPLE
            ),
        ]);
    }
}
