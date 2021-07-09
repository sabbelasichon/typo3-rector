<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Resources\Icons\Rector;

use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\Application\File;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Contract\FileProcessor\Resources\IconRectorInterface;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileSystem;

final class IconsRector implements IconRectorInterface
{
    public function __construct(
        private ParameterProvider $parameterProvider,
        private RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        private SmartFileSystem $smartFileSystem
    ) {
    }

    public function refactorFile(File $file): void
    {
        $smartFileInfo = $file->getSmartFileInfo();

        $newFullPath = $this->createIconPath($file);

        $this->createDeepDirectory($newFullPath);

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

    private function createDeepDirectory(string $newFullPath): void
    {
        if ($this->shouldSkip()) {
            return;
        }

        $this->smartFileSystem->mkdir(dirname($newFullPath));
    }

    private function createIconPath(File $file): string
    {
        $smartFileInfo = $file->getSmartFileInfo();

        $realPath = $smartFileInfo->getRealPathDirectory();
        $relativeTargetFilePath = sprintf('/Resources/Public/Icons/Extension.%s', $smartFileInfo->getExtension());

        return $realPath . $relativeTargetFilePath;
    }

    private function shouldSkip(): bool
    {
        if (!$this->parameterProvider->provideBoolParameter(
            Option::DRY_RUN
        )) {
            return false;
        }
        return ! StaticPHPUnitEnvironment::isPHPUnitRun();
    }
}
