<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Resources\Icons\Rector\v8\v3;

use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\ValueObject\Application\File;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Ssch\TYPO3Rector\Contract\FileProcessor\Resources\IconRectorInterface;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.3/Feature-77349-AdditionalLocationsForExtensionIcons.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Resources\Icons\Rector\v8\v3\IconsRector\IconsRectorTest
 */
final class IconsRector implements IconRectorInterface
{
    /**
     * @readonly
     */
    private RemovedAndAddedFilesCollector $removedAndAddedFilesCollector;

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    public function __construct(RemovedAndAddedFilesCollector $removedAndAddedFilesCollector, FileInfoFactory $fileInfoFactory)
    {
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
        $this->fileInfoFactory = $fileInfoFactory;
    }

    public function refactorFile(File $file): void
    {
        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        $newFullPath = $this->createIconPath($file);

        $this->removedAndAddedFilesCollector->addAddedFile(
            new AddedFileWithContent($newFullPath, $smartFileInfo->getContents())
        );
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

    private function createIconPath(File $file): string
    {
        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        $realPath = dirname($smartFileInfo->getRealPath());
        $relativeTargetFilePath = sprintf('/Resources/Public/Icons/Extension.%s', $smartFileInfo->getExtension());

        return $realPath . $relativeTargetFilePath;
    }
}
