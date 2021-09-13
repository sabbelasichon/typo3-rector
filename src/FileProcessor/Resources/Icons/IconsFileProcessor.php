<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Resources\Icons;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Contract\FileProcessor\Resources\IconRectorInterface;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.3/Feature-77349-AdditionalLocationsForExtensionIcons.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Resources\Icons\IconsProcessor\IconsProcessorTest
 */
final class IconsFileProcessor implements FileProcessorInterface
{
    /**
     * @var string
     */
    private const EXT_ICON_NAME = 'ext_icon';

    /**
     * @param IconRectorInterface[] $iconsRector
     */
    public function __construct(
        private FilesFinder $filesFinder,
        private SmartFileSystem $smartFileSystem,
        private array $iconsRector
    ) {
    }

    public function process(File $file, Configuration $configuration): void
    {
        foreach ($this->iconsRector as $iconRector) {
            $iconRector->refactorFile($file);
        }
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        if ($this->shouldSkip($smartFileInfo->getFilenameWithoutExtension())) {
            return false;
        }

        $extEmConfSmartFileInfo = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($smartFileInfo);

        if (! $extEmConfSmartFileInfo instanceof SmartFileInfo) {
            return false;
        }

        return ! $this->smartFileSystem->exists($this->createIconPath($file));
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

    private function shouldSkip(string $filenameWithoutExtension): bool
    {
        if (self::EXT_ICON_NAME === $filenameWithoutExtension) {
            return false;
        }

        if (StaticPHPUnitEnvironment::isPHPUnitRun() && str_contains($filenameWithoutExtension, self::EXT_ICON_NAME)) {
            return false;
        }

        return true;
    }
}
