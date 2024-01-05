<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Resources\Files\Rector\v12\v0;

use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\ValueObject\Application\File;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Contract\FileProcessor\Resources\FileRectorInterface;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Symfony\Component\Finder\SplFileInfo;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96518-Ext_typoscript_txtFilesNotIncludedAnymore.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Resources\Files\Rector\v12\v0\RenameExtTypoScriptFiles\RenameExtTypoScriptFilesFileRectorTest
 */
final class RenameExtTypoScriptFilesFileRector implements FileRectorInterface
{
    /**
     * @readonly
     */
    private RemovedAndAddedFilesCollector $removedAndAddedFilesCollector;

    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    public function __construct(RemovedAndAddedFilesCollector $removedAndAddedFilesCollector, FilesFinder $filesFinder, FileInfoFactory $fileInfoFactory)
    {
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
        $this->filesFinder = $filesFinder;
        $this->fileInfoFactory = $fileInfoFactory;
    }

    public function refactorFile(File $file): void
    {
        if ($this->shouldSkip($file)) {
            return;
        }

        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        $newFileName = $smartFileInfo->getPath() . '/' . pathinfo(
            $smartFileInfo->getFilename()
        )['filename'] . '.typoscript';

        $this->removedAndAddedFilesCollector->removeFile($file->getFilePath());
        $this->removedAndAddedFilesCollector->addAddedFile(
            new AddedFileWithContent($newFileName, $file->getFileContent())
        );
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename ext_typoscript_*.txt to ext_typoscript_*.typoscript', [
            new CodeSample(
                <<<'CODE_SAMPLE'
ext_typoscript_constants.txt
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
ext_typoscript_constants.typoscript
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(File $file): bool
    {
        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        $extEmConfFile = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($smartFileInfo);

        if (! $extEmConfFile instanceof SplFileInfo) {
            return true;
        }

        if ($extEmConfFile->getPath() !== $smartFileInfo->getPath()) {
            return true;
        }

        if ($smartFileInfo->getBasename() === 'ext_typoscript_setup.txt') {
            return false;
        }

        if ($smartFileInfo->getBasename() === 'ext_typoscript_constants.txt') {
            return false;
        }

        // This is a guard clause to prevent the further checks if not in test mode
        if (! StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return true;
        }

        return ! str_ends_with($smartFileInfo->getBasename(), '.txt');
    }
}
