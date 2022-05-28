<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Command;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\ComposerPackages\Collection\ExtensionCollection;
use Ssch\TYPO3Rector\ComposerPackages\ComposerConfigurationPathResolver;
use Ssch\TYPO3Rector\ComposerPackages\Enum\Typo3Bounds;
use Ssch\TYPO3Rector\ComposerPackages\PackagistPackageResolver;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddPackageVersionRector;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddReplacePackageRector;
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
        private readonly AddPackageVersionRector $addPackageVersionRector,
        private readonly AddReplacePackageRector $replacePackageRector,
        private readonly CurrentFileProvider $currentFileProvider
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

            $this->addReplacePackages($extensionCollection);

            foreach ($typo3Versions as $typo3Version) {
                $extension = $extensionCollection->findHighestVersion($typo3Version);
                if (! $extension instanceof ExtensionVersion) {
                    continue;
                }

                $smartFileInfo = $this->composerConfigurationPathResolver->resolveByTypo3Version($typo3Version);
                if (! $smartFileInfo instanceof SmartFileInfo) {
                    continue;
                }

                $file = new File($smartFileInfo, $smartFileInfo->getContents());
                $this->currentFileProvider->setFile($file);

                $nodes = $this->rectorParser->parseFile($smartFileInfo);
                $this->decorateNamesToFullyQualified($nodes);

                $nodeTraverser = new NodeTraverser();

                $this->addPackageVersionRector->setExtension($extension);

                $nodeTraverser->addVisitor($this->addPackageVersionRector);
                $nodes = $nodeTraverser->traverse($nodes);

                $changedSetConfigContent = $this->betterStandardPrinter->prettyPrintFile($nodes);
                $this->smartFileSystem->dumpFile($smartFileInfo->getRealPath(), $changedSetConfigContent);
            }
        }

        $progressBar->finish();

        return Command::SUCCESS;
    }

    /**
     * @param Node[] $nodes
     */
    private function decorateNamesToFullyQualified(array $nodes): void
    {
        // decorate nodes with names first
        $nameResolverNodeTraverser = new NodeTraverser();
        $nameResolverNodeTraverser->addVisitor(new NameResolver());
        $nameResolverNodeTraverser->traverse($nodes);
    }

    /**
     * @return Typo3Version[]
     */
    private function createTypo3Versions(): array
    {
        $typo3Versions = [];
        foreach (Typo3Bounds::UPPER_BOUNDS as $version) {
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

        $file = new File($smartFileInfo, $smartFileInfo->getContents());
        $this->currentFileProvider->setFile($file);

        $nodes = $this->rectorParser->parseFile($smartFileInfo);
        $this->decorateNamesToFullyQualified($nodes);

        $nodeTraverser = new NodeTraverser();
        $this->replacePackageRector->configure($extensionCollection->getRenamePackages());

        $nodeTraverser->addVisitor($this->replacePackageRector);
        $nodes = $nodeTraverser->traverse($nodes);

        $changedSetConfigContent = $this->betterStandardPrinter->prettyPrintFile($nodes);
        $this->smartFileSystem->dumpFile($smartFileInfo->getRealPath(), $changedSetConfigContent);
    }
}
