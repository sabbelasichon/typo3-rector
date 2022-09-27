<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Resources\Files\Rector\v12\v0;

use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\ValueObject\Application\File;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Contract\FileProcessor\Resources\FileRectorInterface;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

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

    public function __construct(RemovedAndAddedFilesCollector $removedAndAddedFilesCollector, FilesFinder $filesFinder)
    {
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
        $this->filesFinder = $filesFinder;
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

        $smartFileInfo = new SmartFileInfo($file->getFilePath());

        $newFileName = $smartFileInfo->getPath() . $smartFileInfo->getBasenameWithoutSuffix() . '.typoscript';

        $this->removedAndAddedFilesCollector->addMovedFile($file, $newFileName);
    }

    private function shouldSkip(File $file): bool
    {
        $smartFileInfo = new SmartFileInfo($file->getFilePath());

        $extEmConfFile = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($smartFileInfo);

        if (! $extEmConfFile instanceof SmartFileInfo) {
            return true;
        }

        // Test mode is handled differently
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return $this->shouldSkipInTestMode($smartFileInfo);
        }

        if (! str_ends_with($smartFileInfo->getRelativeFilePath(), 'Configuration/TypoScript/')) {
            return true;
        }

        if ('constants.txt' === $smartFileInfo->getBasename()) {
            return false;
        }

        return 'setup.txt' !== $smartFileInfo->getBasename();
    }

    private function shouldSkipInTestMode(SmartFileInfo $smartFileInfo): bool
    {
        return ! str_ends_with($smartFileInfo->getBasename(), '.txt');
    }
}
