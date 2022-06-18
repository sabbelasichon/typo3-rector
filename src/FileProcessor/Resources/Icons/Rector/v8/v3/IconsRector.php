<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Resources\Icons\Rector\v8\v3;

use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\Application\File;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Contract\FileProcessor\Resources\IconRectorInterface;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.3/Feature-77349-AdditionalLocationsForExtensionIcons.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Resources\Icons\Rector\v8\v3\IconsRector\IconsRectorTest
 */
final class IconsRector implements IconRectorInterface
{
    public function __construct(
        private readonly ParameterProvider $parameterProvider,
        private readonly RemovedAndAddedFilesCollector $removedAndAddedFilesCollector
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

        $directory = dirname($newFullPath);
        if (is_dir($directory)) {
            return;
        }

        if (! mkdir($directory, 0777, true)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }
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
        return $this->parameterProvider->provideBoolParameter(
            Option::DRY_RUN
        ) && ! StaticPHPUnitEnvironment::isPHPUnitRun();
    }
}
