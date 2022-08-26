<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\v12\v0;

use Nette\Utils\Strings;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class AbstractMessageGetSeverityFluidRector implements FluidRectorInterface
{
    /**
     * @var string
     */
    private const PATTERN = '#{status.severity}#imsU';

    /**
     * @var string
     */
    private const REPLACEMENT = '{status.severity.value}';

    public function transform(File $file): void
    {
        $content = $file->getFileContent();
        $content = Strings::replace($content, self::PATTERN, self::REPLACEMENT);
        $file->changeFileContent($content);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use <f:defaultCase> instead of <f:case default="1">', [
            new CodeSample(
                <<<'CODE_SAMPLE'
<div class="{severityClassMapping.{status.severity}}">
    <!-- stuff happens here -->
</div>
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
<div class="{severityClassMapping.{status.severity.value}}">
    <!-- stuff happens here -->
</div>
CODE_SAMPLE
            ),
        ]);
    }
}
