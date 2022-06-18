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

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96518-Ext_typoscript_txtFilesNotIncludedAnymore.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Resources\Files\Rector\v12\v0\RenameExtTypoScriptFiles\RenameExtTypoScriptFilesFileRectorTest
 */
final class RenameExtTypoScriptFilesFileRector implements FileRectorInterface
{
    public function __construct(
        private readonly RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        private readonly FilesFinder $filesFinder
    ) {
    }

    public function refactorFile(File $file): void
    {
        if ($this->shouldSkip($file)) {
            return;
        }

        $smartFileInfo = $file->getSmartFileInfo();

        $newFileName = $smartFileInfo->getPath() . $smartFileInfo->getBasenameWithoutSuffix() . '.typoscript';

        $this->removedAndAddedFilesCollector->addMovedFile($file, $newFileName);
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
        $smartFileInfo = $file->getSmartFileInfo();

        $extEmConfFile = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($smartFileInfo);

        if (! $extEmConfFile instanceof SmartFileInfo) {
            return true;
        }

        if ($extEmConfFile->getPath() !== $smartFileInfo->getPath()) {
            return true;
        }

        if ('ext_typoscript_setup.txt' === $smartFileInfo->getBasename()) {
            return false;
        }

        if ('ext_typoscript_constants.txt' === $smartFileInfo->getBasename()) {
            return false;
        }

        if (! StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return true;
        }

        if (str_ends_with($smartFileInfo->getBasename(), 'ext_typoscript_constants.txt')) {
            return false;
        }

        return ! str_ends_with($smartFileInfo->getBasename(), 'ext_typoscript_setup.txt');
    }
}
