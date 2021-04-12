<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Resources\Icons;

use Rector\Core\Configuration\Configuration;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\SmartFileSystem\SmartFileSystem;

final class IconsProcessor implements FileProcessorInterface
{
    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(
        SmartFileSystem $smartFileSystem,
        SymfonyStyle $symfonyStyle,
        Configuration $configuration
    ) {
        $this->smartFileSystem = $smartFileSystem;
        $this->symfonyStyle = $symfonyStyle;
        $this->configuration = $configuration;
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

        $extEmConf = sprintf('%s/ext_emconf.php', rtrim(dirname($smartFileInfo->getRealPath()), '/'));

        return $this->smartFileSystem->exists($extEmConf);
    }

    public function getSupportedFileExtensions(): array
    {
        return ['png', 'gif', 'svg'];
    }

    private function processFile(File $file): void
    {
        $smartFileInfo = $file->getSmartFileInfo();

        $relativeFilePath = dirname($smartFileInfo->getRelativeFilePath());
        $realPath = dirname($smartFileInfo->getRealPath());
        $relativeTargetFilePath = sprintf('/Resources/Public/Icons/Extension.%s', $smartFileInfo->getExtension());

        $newFullPath = $realPath . $relativeTargetFilePath;
        if ($this->configuration->isDryRun()) {
            $message = sprintf(
                'File "%s" will be moved to %s',
                $relativeFilePath,
                $relativeFilePath . $relativeTargetFilePath
            );
            $this->symfonyStyle->info($message);
        } elseif (! $this->smartFileSystem->exists($newFullPath)) {
            $message = sprintf('File "%s" moved to %s', $relativeFilePath, $relativeFilePath . $relativeTargetFilePath);

            $this->symfonyStyle->info($message);
            if (! $this->smartFileSystem->exists(dirname($newFullPath))) {
                $this->smartFileSystem->mkdir(dirname($newFullPath));
            }

            $this->smartFileSystem->rename($smartFileInfo->getRealPath(), $newFullPath, true);
        } else {
            $message = sprintf('File "%s" already exists.', $newFullPath);
            $this->symfonyStyle->warning($message);
        }
    }
}
