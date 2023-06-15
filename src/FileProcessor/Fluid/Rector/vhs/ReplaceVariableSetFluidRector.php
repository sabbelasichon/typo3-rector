<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs;

use Nette\Utils\Strings;
use Rector\Core\Console\Output\RectorOutputStyle;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceVariableSetFluidRector implements FluidRectorInterface
{
    /**
     * @var string
     */
    private const PATTERN_STRICT = '#(v|vhs):(variable.set)([\s(]name[:=]["\'])([a-z]+)(["\'])#imsU';

    /**
     * @var string
     */
    private const PATTERN_DOT = '#(v|vhs):(variable.set)([\s(]name[:=]["\'])([a-z\.]+)(["\'])#imsU';

    /**
     * @var string
     */
    private const PATTERN_LEFTOVERS = '#(v|vhs):(variable.set)#imsU';

    /**
     * @readonly
     */
    private RectorOutputStyle $rectorOutputStyle;

    public function __construct(RectorOutputStyle $rectorOutputStyle)
    {
        $this->rectorOutputStyle = $rectorOutputStyle;
    }

    public function transform(File $file): void
    {
        $content = $file->getFileContent();

        $content = Strings::replace($content, self::PATTERN_STRICT, 'f:variable$3$4$5');

        if (Strings::matchAll($content, self::PATTERN_DOT)) {
            $this->rectorOutputStyle->warning('There\'s occurrences of v:variable.set that contain a dot in its name attribute and thus cannot be migrated to f:variable!');
        }

        if (Strings::matchAll($content, self::PATTERN_LEFTOVERS)) {
            $this->rectorOutputStyle->warning('There\'s occurrences of v:variable.set that couldn\'t be migrated automatically. Migrate them manually!');
        }

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
