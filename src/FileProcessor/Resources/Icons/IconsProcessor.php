<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Resources\Icons;

use Nette\Utils\Strings;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Resources\IconRectorInterface;
use Ssch\TYPO3Rector\Helper\FilesFinder;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.3/Feature-77349-AdditionalLocationsForExtensionIcons.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Resources\Icons\IconsProcessor\IconsProcessorTest
 */
final class IconsProcessor implements FileProcessorInterface
{
    /**
     * @param IconRectorInterface[] $iconsRector
     */
    public function __construct(
        private FilesFinder $filesFinder,
        private array $iconsRector
    ) {
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        foreach ($files as $file) {
            foreach ($this->iconsRector as $iconRector) {
                $iconRector->refactorFile($file);
            }
        }
    }

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        if (! Strings::contains($smartFileInfo->getFilename(), 'ext_icon')) {
            return false;
        }

        $extEmConfSmartFileInfo = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($smartFileInfo);

        if (null === $extEmConfSmartFileInfo) {
            return false;
        }

        return ! file_exists($this->createIconPath($file));
    }

    public function getSupportedFileExtensions(): array
    {
        return ['png', 'gif', 'svg'];
    }

    private function createIconPath(File $file): string
    {
        $smartFileInfo = $file->getSmartFileInfo();

        $realPath = $smartFileInfo->getRealPathDirectory();
        $relativeTargetFilePath = sprintf('/Resources/Public/Icons/Extension.%s', $smartFileInfo->getExtension());

        return $realPath . $relativeTargetFilePath;
    }
}
