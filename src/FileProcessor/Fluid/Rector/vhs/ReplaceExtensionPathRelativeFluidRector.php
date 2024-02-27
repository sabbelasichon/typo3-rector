<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs;

use Nette\Utils\Strings;
use Rector\Core\Console\Output\RectorOutputStyle;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceExtensionPathRelativeFluidRector implements FluidRectorInterface
{
    /**
     * @var string
     */
    private const PATTERN_INLINE = '#{(v|vhs):extension\.path\.relative\(extensionName:(\s?)["\']([a-z0-9_]+)["\']\)}Resources\/Public\/(\S+)\.([a-z0-9]{2,4})#ims';

    /**
     * @var string
     */
    private const PATTERN_LEFTOVERS = '#(v|vhs):(extension\.path\.relative)#ims';

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

        $content = Strings::replace(
            $content,
            self::PATTERN_INLINE,
            '{f:uri.resource(extensionName:\'\3\',path:\'\4.\5\')}'
        );

        if (Strings::matchAll($content, self::PATTERN_LEFTOVERS)) {
            $this->rectorOutputStyle->warning(
                'There\'s occurrences of v:extension.path.relative that couldn\'t be migrated automatically. Migrate them manually!'
            );
        }

        $file->changeFileContent($content);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use <f:uri.resource> instead of <v:extension.path.relative>', [
            new CodeSample(
                <<<'CODE_SAMPLE'
{v:extension.path.relative(extensionName:'my_extension')}Resources/Public/Css/style.css
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
{f:uri.resource(extensionName:'my_extension',path:'Css/style.css')}
CODE_SAMPLE
            ),
        ]);
    }
}
