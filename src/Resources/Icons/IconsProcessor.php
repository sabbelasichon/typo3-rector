<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Resources\Icons;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\Resources\IconRectorInterface;
use Ssch\TYPO3Rector\Helper\FilesFinder;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.3/Feature-77349-AdditionalLocationsForExtensionIcons.html
 * @see \Ssch\TYPO3Rector\Tests\Resources\Icons\IconsProcessor\IconsProcessorTest
 */
final class IconsProcessor implements FileProcessorInterface
{
    /**
     * @var FilesFinder
     */
    private $filesFinder;

    /**
     * @var IconRectorInterface[]
     */
    private $iconsRector = [];

    /**
     * @param IconRectorInterface[] $iconsRector
     */
    public function __construct(FilesFinder $filesFinder, array $iconsRector)
    {
        $this->filesFinder = $filesFinder;
        $this->iconsRector = $iconsRector;
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

        if (! in_array($smartFileInfo->getFilename(), ['ext_icon.png', 'ext_icon.svg', 'ext_icon.gif'], true)) {
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
