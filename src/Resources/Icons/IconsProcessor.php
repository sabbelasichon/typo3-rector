<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Resources\Icons;

use Rector\Core\Configuration\Configuration;
use Rector\Core\Contract\Processor\NonPhpFileProcessorInterface;
use Rector\Core\ValueObject\NonPhpFile\NonPhpFileChange;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class IconsProcessor implements NonPhpFileProcessorInterface
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

    public function process(SmartFileInfo $smartFileInfo): ?NonPhpFileChange
    {
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

        return null;
    }

    public function supports(SmartFileInfo $smartFileInfo): bool
    {
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
}
