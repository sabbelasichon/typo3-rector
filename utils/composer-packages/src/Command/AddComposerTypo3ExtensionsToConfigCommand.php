<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Command;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use Rector\Core\PhpParser\Parser\Parser;
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
use Symplify\SmartFileSystem\SmartFileSystem;

final class AddComposerTypo3ExtensionsToConfigCommand extends Command
{
    /**
     * @var PackageResolver
     */
    private $packageResolver;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var ComposerConfigurationPathResolver
     */
    private $composerConfigurationPathResolver;

    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    /**
     * @var BetterStandardPrinter
     */
    private $betterStandardPrinter;

    /**
     * @var AddPackageVersionRector
     */
    private $addPackageVersionRector;

    /**
     * @var RemovePackageVersionsRector
     */
    private $removePackageVersionsRector;

    /**
     * @var AddReplacePackageRector
     */
    private $replacePackageRector;

    /**
     * @var CurrentFileProvider
     */
    private $currentFileProvider;

    public function __construct(
        PackageResolver $packageResolver,
        Parser $parser,
        ComposerConfigurationPathResolver $composerConfigurationPathResolver,
        SmartFileSystem $smartFileSystem,
        BetterStandardPrinter $betterStandardPrinter,
        AddPackageVersionRector $addPackageVersionRector,
        RemovePackageVersionsRector $removePackageVersionsRector,
        AddReplacePackageRector $replacePackageRector,
        CurrentFileProvider $currentFileProvider
    ) {
        parent::__construct();

        $this->packageResolver = $packageResolver;
        $this->parser = $parser;
        $this->composerConfigurationPathResolver = $composerConfigurationPathResolver;
        $this->smartFileSystem = $smartFileSystem;
        $this->betterStandardPrinter = $betterStandardPrinter;
        $this->addPackageVersionRector = $addPackageVersionRector;
        $this->removePackageVersionsRector = $removePackageVersionsRector;
        $this->replacePackageRector = $replacePackageRector;
        $this->currentFileProvider = $currentFileProvider;
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

                if (null === $smartFileInfo) {
                    continue;
                }

                $file = new File($smartFileInfo, $smartFileInfo->getContents());
                $this->currentFileProvider->setFile($file);

                $nodes = $this->parser->parseFileInfo($smartFileInfo);
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

    /**
     * @param Typo3Version[] $typo3Versions
     */
    private function resetComposerExtensions(array $typo3Versions): void
    {
        foreach ($typo3Versions as $typo3Version) {
            $smartFileInfo = $this->composerConfigurationPathResolver->resolveByTypo3Version($typo3Version);

            if (null === $smartFileInfo) {
                continue;
            }

            $nodes = $this->parser->parseFileInfo($smartFileInfo);
            $this->decorateNamesToFullyQualified($nodes);

            $nodeTraverser = new NodeTraverser();

            $nodeTraverser->addVisitor($this->removePackageVersionsRector);
            $nodes = $nodeTraverser->traverse($nodes);

            $changedSetConfigContent = $this->betterStandardPrinter->prettyPrintFile($nodes);
            $this->smartFileSystem->dumpFile($smartFileInfo->getRealPath(), $changedSetConfigContent);
        }
    }

    private function addReplacePackages(ExtensionCollection $collection): void
    {
        $smartFileInfo = $this->composerConfigurationPathResolver->replacePackages();

        if (null === $smartFileInfo) {
            return;
        }

        $file = new File($smartFileInfo, $smartFileInfo->getContents());
        $this->currentFileProvider->setFile($file);

        $nodes = $this->parser->parseFileInfo($smartFileInfo);
        $this->decorateNamesToFullyQualified($nodes);

        $nodeTraverser = new NodeTraverser();
        $this->replacePackageRector->setReplacePackages($collection->getReplacePackages());

        $nodeTraverser->addVisitor($this->replacePackageRector);
        $nodes = $nodeTraverser->traverse($nodes);

        $changedSetConfigContent = $this->betterStandardPrinter->prettyPrintFile($nodes);
        $this->smartFileSystem->dumpFile($smartFileInfo->getRealPath(), $changedSetConfigContent);
    }
}
