<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs;

use Nette\Utils\Strings;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceVariableSetFluidRector implements FluidRectorInterface
{
    /**
     * @var string
     */
    private const PATTERN = '#(v|vhs):variable.set#imsU';

    /**
     * @var string
     */
    private const REPLACEMENT = 'f:variable';

    public function transform(File $file): void
    {
        $content = $file->getFileContent();

        $content = Strings::replace($content, self::PATTERN, self::REPLACEMENT);

        $file->changeFileContent($content);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use <f:variable> instead of <v:variable.set>', [
            new CodeSample(
                <<<'CODE_SAMPLE'
<v:variable.set name="myvariable" value="a string value" />
{myvariable -> v:variable.set(name:'othervariable')}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
<f:variable name="myvariable" value="a string value" />
{myvariable -> f:variable(name:'othervariable')}
CODE_SAMPLE
            ),
        ]);
    }
}
