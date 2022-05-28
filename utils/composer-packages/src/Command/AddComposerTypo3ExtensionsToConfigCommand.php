<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Command;

use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Ssch\TYPO3Rector\ComposerPackages\Collection\ExtensionCollection;
use Ssch\TYPO3Rector\ComposerPackages\ComposerConfigurationPathResolver;
use Ssch\TYPO3Rector\ComposerPackages\NodeDecorator\AddPackageVersionDecorator;
use Ssch\TYPO3Rector\ComposerPackages\NodeDecorator\AddReplacePackageDecorator;
use Ssch\TYPO3Rector\ComposerPackages\PackageParser;
use Ssch\TYPO3Rector\ComposerPackages\PackagistPackageResolver;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class AddComposerTypo3ExtensionsToConfigCommand extends Command
{
    public function __construct(
        private readonly PackagistPackageResolver $packagistPackageResolver,
        private readonly RectorParser $rectorParser,
        private readonly ComposerConfigurationPathResolver $composerConfigurationPathResolver,
        private readonly SmartFileSystem $smartFileSystem,
        private readonly BetterStandardPrinter $betterStandardPrinter,
        private readonly AddPackageVersionDecorator $addPackageVersionDecorator,
        private readonly AddReplacePackageDecorator $addReplacePackageDecorator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('typo3-extensions');
        $this->setDescription('[DEV] Add TYPO3 extensions from packagist.org to composer configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $typo3Versions = $this->createTypo3Versions();

        $composerPackages = $this->packagistPackageResolver->findAllPackagesByType('typo3-cms-extension');

        $progressBar = new ProgressBar($output, count($composerPackages));

        foreach ($composerPackages as $composerPackage) {
            $extensionCollection = $this->packagistPackageResolver->findPackage($composerPackage);

            $progressBar->advance();

            if (0 === count($extensionCollection)) {
                continue;
            }

            foreach ($typo3Versions as $typo3Version) {
                $extensionVersion = $extensionCollection->findHighestVersion($typo3Version);
                if (! $extensionVersion instanceof ExtensionVersion) {
                    continue;
                }

                $smartFileInfo = $this->composerConfigurationPathResolver->resolveByTypo3Version($typo3Version);
                if (! $smartFileInfo instanceof SmartFileInfo) {
                    continue;
                }

                $nodes = $this->rectorParser->parseFile($smartFileInfo);
                $this->addPackageVersionDecorator->refactor($nodes, $extensionVersion);

                $changedSetConfigContent = $this->betterStandardPrinter->prettyPrintFile($nodes);
                $this->smartFileSystem->dumpFile($smartFileInfo->getRealPath(), $changedSetConfigContent);
            }
        }

        $progressBar->finish();

        return Command::SUCCESS;
    }

    /**
     * @return Typo3Version[]
     */
    private function createTypo3Versions(): array
    {
        $typo3Versions = [];
        foreach (PackageParser::TYPO3_UPPER_BOUNDS as $version) {
            $typo3Versions[] = new Typo3Version($version);
        }

        return $typo3Versions;
    }

    private function addReplacePackages(ExtensionCollection $extensionCollection): void
    {
        $smartFileInfo = $this->composerConfigurationPathResolver->replacePackages();
        if (! $smartFileInfo instanceof SmartFileInfo) {
            return;
        }

        $nodes = $this->rectorParser->parseFile($smartFileInfo);

        $changedSetConfigContent = $this->betterStandardPrinter->prettyPrintFile($nodes);
        $this->smartFileSystem->dumpFile($smartFileInfo->getRealPath(), $changedSetConfigContent);
    }
}
