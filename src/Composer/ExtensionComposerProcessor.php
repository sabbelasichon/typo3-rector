<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Composer;

use Ergebnis\Json\Printer\Printer;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\EditorConfig\EditorConfigParser;
use Ssch\TYPO3Rector\ValueObject\EditorConfigConfiguration;
use Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\ComposerJsonManipulator\Printer\ComposerJsonPrinter;

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
     * @var ComposerModifier
     */
    private $composerModifier;

    /**
     * @var CurrentFileProvider
     */
    private $currentFileProvider;

    /**
     * @var EditorConfigParser
     */
    private $editorConfigParser;

    /**
     * @var Printer
     */
    private $printer;

    public function __construct(
        ComposerJsonFactory $composerJsonFactory,
        ComposerJsonPrinter $composerJsonPrinter,
        ComposerModifier $composerModifier,
        CurrentFileProvider $currentFileProvider,
        EditorConfigParser $editorConfigParser,
        Printer $printer
    ) {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->composerJsonPrinter = $composerJsonPrinter;
        $this->composerModifier = $composerModifier;
        $this->currentFileProvider = $currentFileProvider;
        $this->editorConfigParser = $editorConfigParser;
        $this->printer = $printer;
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        // to avoid modification of file
        if (! $this->composerModifier->enabled()) {
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
        $this->composerModifier->modify($composerJson);

        // nothing has changed
        if ($oldComposerJson->getJsonArray() === $composerJson->getJsonArray()) {
            return;
        }

        $defaultEditorConfiguration = new EditorConfigConfiguration(
            EditorConfigConfiguration::SPACE,
            2,
            EditorConfigConfiguration::LINE_FEED
        );
        $editorConfiguration = $this->editorConfigParser->extractConfigurationForFile(
            $smartFileInfo,
            $defaultEditorConfiguration
        );

        $json = $this->composerJsonPrinter->printToString($composerJson);

        $indent = str_pad('', $editorConfiguration->getIndentSize());
        if ($editorConfiguration->getIsTab()) {
            $indent = "\t";
        }

        $newContent = $this->printer->print($json, $indent);

        $file->changeFileContent($newContent);
    }
}
