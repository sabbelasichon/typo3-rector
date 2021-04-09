<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Composer;

use Rector\Core\Contract\Processor\NonPhpFileProcessorInterface;
use Rector\Core\ValueObject\NonPhpFile\NonPhpFileChange;
use Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\ComposerJsonManipulator\Printer\ComposerJsonPrinter;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ExtensionComposerProcessor implements NonPhpFileProcessorInterface
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

    public function __construct(
        ComposerJsonFactory $composerJsonFactory,
        ComposerJsonPrinter $composerJsonPrinter,
        ComposerModifier $composerModifier
    ) {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->composerJsonPrinter = $composerJsonPrinter;
        $this->composerModifier = $composerModifier;
    }

    public function process(SmartFileInfo $smartFileInfo): ?NonPhpFileChange
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

        $oldContent = $this->composerJsonPrinter->printToString($oldComposerJson);
        $newContent = $this->composerJsonPrinter->printToString($composerJson);

        return new NonPhpFileChange($oldContent, $newContent);
    }

    public function supports(SmartFileInfo $smartFileInfo): bool
    {
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
}
