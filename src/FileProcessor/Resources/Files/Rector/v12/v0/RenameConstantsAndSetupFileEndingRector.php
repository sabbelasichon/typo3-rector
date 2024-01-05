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

final class RenameConstantsAndSetupFileEndingRector implements FileRectorInterface
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

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename setup.txt and constants.txt to *.typoscript', [
            new CodeSample(
                <<<'CODE_SAMPLE'
setup.txt
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
setup.typoscript
CODE_SAMPLE
            ),
        ]);
    }

    public function refactorFile(File $file): void
    {
        if ($this->shouldSkip($file)) {
            return;
        }

        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        $newFileName = $smartFileInfo->getPath() . pathinfo($smartFileInfo->getFilename())['filename'] . '.typoscript';

        $this->removedAndAddedFilesCollector->removeFile($file->getFilePath());
        $this->removedAndAddedFilesCollector->addAddedFile(
            new AddedFileWithContent($newFileName, $file->getFileContent())
        );
    }

    private function shouldSkip(File $file): bool
    {
        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        $extEmConfFile = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($smartFileInfo);

        if ($extEmConfFile === null) {
            return true;
        }

        // Test mode is handled differently
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return $this->shouldSkipInTestMode($smartFileInfo);
        }

        if (! str_ends_with($smartFileInfo->getRelativePathname(), 'Configuration/TypoScript/')) {
            return true;
        }

        if ($smartFileInfo->getBasename() === 'constants.txt') {
            return false;
        }

        return $smartFileInfo->getBasename() !== 'setup.txt';
    }

    private function shouldSkipInTestMode(SplFileInfo $smartFileInfo): bool
    {
        return ! str_ends_with($smartFileInfo->getBasename(), '.txt');
    }
}
