<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Composer;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\ComposerJsonManipulator\Printer\ComposerJsonPrinter;

/**
 * @see \Ssch\TYPO3Rector\Tests\Composer\ExtensionComposerProcessor\ExtensionComposerProcessorTest
 */
final class ExtensionComposerProcessor implements FileProcessorInterface
{
    /**
     * @var ComposerJsonFactory
     */
    private $composerJsonFactory;

    /**
     * @var ComposerJsonPrinter
     */
    private $composerJsonPrinter;

    /**
     * @var CurrentFileProvider
     */
    private $currentFileProvider;

    /**
     * @var ExtensionComposerRectorInterface[]
     */
    private $composerRectors = [];

    /**
     * @param ExtensionComposerRectorInterface[] $composerRectors
     */
    public function __construct(
        ComposerJsonFactory $composerJsonFactory,
        ComposerJsonPrinter $composerJsonPrinter,
        CurrentFileProvider $currentFileProvider,
        array $composerRectors
    ) {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->composerJsonPrinter = $composerJsonPrinter;
        $this->composerRectors = $composerRectors;
        $this->currentFileProvider = $currentFileProvider;
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        if ([] === $this->composerRectors) {
            return;
        }

        foreach ($files as $file) {
            $this->processFile($file);
        }
    }

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();
        if ('composer.json' !== $smartFileInfo->getBasename()) {
            return false;
        }

        return in_array($smartFileInfo->getExtension(), $this->getSupportedFileExtensions(), true);
    }

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array
    {
        return ['json'];
    }

    private function processFile(File $file): void
    {
        $this->currentFileProvider->setFile($file);

        $smartFileInfo = $file->getSmartFileInfo();

        $composerJson = $this->composerJsonFactory->createFromFileInfo($smartFileInfo);

        $oldComposerJson = clone $composerJson;

        foreach ($this->composerRectors as $composerRector) {
            $composerRector->refactor($composerJson);
        }

        // nothing has changed
        if ($oldComposerJson->getJsonArray() === $composerJson->getJsonArray()) {
            return;
        }

        $newFileContent = $this->composerJsonPrinter->printToString($composerJson);

        $file->changeFileContent($newFileContent);
    }
}
