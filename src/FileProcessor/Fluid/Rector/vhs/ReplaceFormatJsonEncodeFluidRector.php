<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs;

use Nette\Utils\Strings;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceFormatJsonEncodeFluidRector implements FluidRectorInterface
{
    /**
     * @var string
     */
    private const PATTERN = '#(v|vhs):format.json.encode#imsU';

    /**
     * @var string
     */
    private const REPLACEMENT = 'f:format.json';

    public function transform(File $file): void
    {
        $content = $file->getFileContent();

        $content = Strings::replace($content, self::PATTERN, self::REPLACEMENT);

        $file->changeFileContent($content);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use <f:format.json> instead of <v:format.json.encode>', [
            new CodeSample(
                <<<'CODE_SAMPLE'
{someArray -> v:format.json.encode()}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
{someArray -> f:format.json()}
CODE_SAMPLE
            ),
        ]);
    }
}
