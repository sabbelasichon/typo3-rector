<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Composer;

use Rector\ChangesReporting\Application\ErrorAndDiffCollector;
use Rector\Core\Configuration\Configuration;
use Ssch\TYPO3Rector\Processor\ProcessorInterface;
use Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\ComposerJsonManipulator\Printer\ComposerJsonPrinter;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ComposerProcessor implements ProcessorInterface
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
     * @var Configuration
     */
    private $configuration;

    /**
     * @var ErrorAndDiffCollector
     */
    private $errorAndDiffCollector;

    /**
     * @var ComposerModifier
     */
    private $composerModifier;

    public function __construct(
        ComposerJsonFactory $composerJsonFactory,
        ComposerJsonPrinter $composerJsonPrinter,
        Configuration $configuration,
        ErrorAndDiffCollector $errorAndDiffCollector,
        ComposerModifier $composerModifier
    ) {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->composerJsonPrinter = $composerJsonPrinter;
        $this->configuration = $configuration;
        $this->errorAndDiffCollector = $errorAndDiffCollector;
        $this->composerModifier = $composerModifier;
    }

    public function process(SmartFileInfo $smartFileInfo): ?string
    {
        // to avoid modification of file
        if (! $this->composerModifier->enabled()) {
            return null;
        }

        $composerJson = $this->composerJsonFactory->createFromFileInfo($smartFileInfo);

        $oldComposerJson = clone $composerJson;
        $this->composerModifier->modify($composerJson);

        // nothing has changed
        if ($oldComposerJson->getJsonArray() === $composerJson->getJsonArray()) {
            return null;
        }

        $this->addComposerJsonFileDiff($oldComposerJson, $composerJson, $smartFileInfo);
        $this->reportFileContentChange($composerJson, $smartFileInfo);

        return null;
    }

    public function canProcess(SmartFileInfo $smartFileInfo): bool
    {
        return in_array($smartFileInfo->getExtension(), $this->allowedFileExtensions(), true);
    }

    /**
     * @return string[]
     */
    public function allowedFileExtensions(): array
    {
        return ['json'];
    }

    private function addComposerJsonFileDiff(
        ComposerJson $oldComposerJson,
        ComposerJson $newComposerJson,
        SmartFileInfo $smartFileInfo
    ): void {
        $newContents = $this->composerJsonPrinter->printToString($newComposerJson);
        $oldContents = $this->composerJsonPrinter->printToString($oldComposerJson);
        $this->errorAndDiffCollector->addFileDiff($smartFileInfo, $newContents, $oldContents);
    }

    private function reportFileContentChange(ComposerJson $composerJson, SmartFileInfo $smartFileInfo): void
    {
        if ($this->configuration->isDryRun()) {
            return;
        }

        $this->composerJsonPrinter->print($composerJson, $smartFileInfo);
    }
}
