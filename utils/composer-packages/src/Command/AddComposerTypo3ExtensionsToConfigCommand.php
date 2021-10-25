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
use Ssch\TYPO3Rector\ComposerPackages\PackageParser;
use Ssch\TYPO3Rector\ComposerPackages\PackageResolver;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddPackageVersionRector;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddReplacePackageRector;
use Ssch\TYPO3Rector\ComposerPackages\Rector\RemovePackageVersionsRector;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class AddComposerTypo3ExtensionsToConfigCommand extends Command
{
    public function __construct(
        private PackageResolver $packageResolver,
        private RectorParser $rectorParser,
        private ComposerConfigurationPathResolver $composerConfigurationPathResolver,
        private SmartFileSystem $smartFileSystem,
        private BetterStandardPrinter $betterStandardPrinter,
        private AddPackageVersionRector $addPackageVersionRector,
        private RemovePackageVersionsRector $removePackageVersionsRector,
        private AddReplacePackageRector $replacePackageRector,
        private CurrentFileProvider $currentFileProvider
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setAliases(['typo3-extensions']);
        $this->setDescription('[DEV] Add TYPO3 extensions from packagist.org to composer configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $typo3Versions = $this->createTypo3Versions();
        $packages = $this->packageResolver->findAllPackagesByType('typo3-cms-extension');

        $progressBar = new ProgressBar($output, count($packages));

        foreach ($packages as $package) {
            $collection = $this->packageResolver->findPackage($package);

            $progressBar->advance();

            if (0 === count($collection)) {
                continue;
            }

            $this->addReplacePackages($collection);

            foreach ($typo3Versions as $typo3Version) {
                $extension = $collection->findHighestVersion($typo3Version);

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

        return ShellCode::SUCCESS;
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
        foreach (PackageParser::TYPO3_UPPER_BOUNDS as $version) {
            $typo3Versions[] = new Typo3Version($version);
        }

        return $typo3Versions;
    }

    private function addReplacePackages(ExtensionCollection $collection): void
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
        $this->replacePackageRector->setReplacePackages($collection->getReplacePackages());

        $nodeTraverser->addVisitor($this->replacePackageRector);
        $nodes = $nodeTraverser->traverse($nodes);

        $changedSetConfigContent = $this->betterStandardPrinter->prettyPrintFile($nodes);
        $this->smartFileSystem->dumpFile($smartFileInfo->getRealPath(), $changedSetConfigContent);
    }
}
