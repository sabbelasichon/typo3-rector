<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Resources;

use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Configuration\Configuration;
use Rector\Core\ValueObject\Application\File;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Ssch\TYPO3Rector\Contract\Resources\IconRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class IconsRector implements IconRectorInterface
{
    public function __construct(private Configuration $configuration, private RemovedAndAddedFilesCollector $removedAndAddedFilesCollector)
    {
    }

    public function refactorFile(File $file): void
    {
        $smartFileInfo = $file->getSmartFileInfo();

        $newFullPath = $this->createIconPath($file);

        $this->removedAndAddedFilesCollector->addAddedFile(
            new AddedFileWithContent($newFullPath, $smartFileInfo->getContents())
        );

        $this->createDeepDirectory($newFullPath);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Copy ext_icon.* to Resources/Icons/Extension.*', [
            new CodeSample(
                <<<'CODE_SAMPLE'
ext_icon.gif
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
Resources/Icons/Extension.gif
CODE_SAMPLE
            ),
        ]);
    }

    private function createDeepDirectory(string $newFullPath): void
    {
        if ($this->configuration->isDryRun()) {
            return;
        }

        $iconsDirectory = dirname($newFullPath);

        if (! is_dir($iconsDirectory)) {
            mkdir($iconsDirectory, 0777, true);
        }
    }

    private function createIconPath(File $file): string
    {
        $smartFileInfo = $file->getSmartFileInfo();

        $realPath = $smartFileInfo->getRealPathDirectory();
        $relativeTargetFilePath = sprintf('/Resources/Public/Icons/Extension.%s', $smartFileInfo->getExtension());

        return $realPath . $relativeTargetFilePath;
    }
}
