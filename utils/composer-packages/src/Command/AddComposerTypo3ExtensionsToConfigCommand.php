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
use Ssch\TYPO3Rector\ComposerPackages\Enum\Typo3UpperBounds;
use Ssch\TYPO3Rector\ComposerPackages\PackagistPackageResolver;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddPackageVersionRector;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddReplacePackageRector;
use Ssch\TYPO3Rector\ComposerPackages\Typo3SetListComposerConfigurationPathResolver;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symplify\SmartFileSystem\SmartFileInfo;

final class AddComposerTypo3ExtensionsToConfigCommand extends Command
{
    /**
     * @readonly
     */
    private PackagistPackageResolver $packagistPackageResolver;

    /**
     * @readonly
     */
    private RectorParser $rectorParser;

    /**
     * @readonly
     */
    private Typo3SetListComposerConfigurationPathResolver $typo3SetListComposerConfigurationPathResolver;

    /**
     * @readonly
     */
    private Filesystem $filesystem;

    /**
     * @readonly
     */
    private BetterStandardPrinter $betterStandardPrinter;

    /**
     * @readonly
     */
    private AddPackageVersionRector $addPackageVersionRector;

    /**
     * @readonly
     */
    private AddReplacePackageRector $replacePackageRector;

    /**
     * @readonly
     */
    private CurrentFileProvider $currentFileProvider;

    public function __construct(
        PackagistPackageResolver $packagistPackageResolver,
        RectorParser $rectorParser,
        Typo3SetListComposerConfigurationPathResolver $typo3SetListComposerConfigurationPathResolver,
        Filesystem $filesystem,
        BetterStandardPrinter $betterStandardPrinter,
        AddPackageVersionRector $addPackageVersionRector,
        AddReplacePackageRector $replacePackageRector,
        CurrentFileProvider $currentFileProvider
    ) {
        $this->packagistPackageResolver = $packagistPackageResolver;
        $this->rectorParser = $rectorParser;
        $this->typo3SetListComposerConfigurationPathResolver = $typo3SetListComposerConfigurationPathResolver;
        $this->filesystem = $filesystem;
        $this->betterStandardPrinter = $betterStandardPrinter;
        $this->addPackageVersionRector = $addPackageVersionRector;
        $this->replacePackageRector = $replacePackageRector;
        $this->currentFileProvider = $currentFileProvider;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('typo3-extensions');
        $this->setDescription('[DEV] Add TYPO3 extensions from packagist.org to composer configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $typo3Versions = Typo3UpperBounds::provide();
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

                $smartFileInfo = $this->typo3SetListComposerConfigurationPathResolver->resolveByTypo3Version(
                    $typo3Version
                );
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
                $this->filesystem->dumpFile($smartFileInfo->getRealPath(), $changedSetConfigContent);
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

    private function addReplacePackages(ExtensionCollection $extensionCollection): void
    {
        $smartFileInfo = $this->typo3SetListComposerConfigurationPathResolver->replacePackages();

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
        $this->filesystem->dumpFile($smartFileInfo->getRealPath(), $changedSetConfigContent);
    }
}
