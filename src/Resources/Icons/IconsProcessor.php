<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Resources\Icons;

use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.3/Feature-77349-AdditionalLocationsForExtensionIcons.html
 * @see \Ssch\TYPO3Rector\Tests\Resources\Icons\IconsProcessor\IconsProcessorTest
 */
final class IconsProcessor implements FileProcessorInterface, RectorInterface
{
    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var RemovedAndAddedFilesCollector
     */
    private $removedAndAddedFilesCollector;

    /**
     * @var FilesFinder
     */
    private $filesFinder;

    public function __construct(
        SmartFileSystem $smartFileSystem,
        Configuration $configuration,
        RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        FilesFinder $filesFinder
    ) {
        $this->smartFileSystem = $smartFileSystem;
        $this->configuration = $configuration;
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
        $this->filesFinder = $filesFinder;
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        foreach ($files as $file) {
            $this->processFile($file);
        }
    }

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        if (! in_array($smartFileInfo->getFilename(), ['ext_icon.png', 'ext_icon.svg', 'ext_icon.gif'], true)) {
            return false;
        }

        $extEmConfSmartFileInfo = $this->filesFinder->findFileRelativeFromGivenFileInfo(
            $smartFileInfo,
            'ext_emconf.php'
        );

        return null !== $extEmConfSmartFileInfo;
    }

    public function getSupportedFileExtensions(): array
    {
        return ['png', 'gif', 'svg'];
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

    private function processFile(File $file): void
    {
        $smartFileInfo = $file->getSmartFileInfo();

        $realPath = $smartFileInfo->getRealPathDirectory();
        $relativeTargetFilePath = sprintf('/Resources/Public/Icons/Extension.%s', $smartFileInfo->getExtension());

        $newFullPath = $realPath . $relativeTargetFilePath;

        $this->removedAndAddedFilesCollector->addAddedFile(
            new AddedFileWithContent($newFullPath, $smartFileInfo->getContents())
        );

        $this->createDeepDirectory($newFullPath);
    }

    private function createDeepDirectory(string $newFullPath): void
    {
        if ($this->configuration->isDryRun()) {
            return;
        }

        $iconsDirectory = dirname($newFullPath);

        if (! $this->smartFileSystem->exists($iconsDirectory)) {
            $this->smartFileSystem->mkdir($iconsDirectory);
        }
    }
}
