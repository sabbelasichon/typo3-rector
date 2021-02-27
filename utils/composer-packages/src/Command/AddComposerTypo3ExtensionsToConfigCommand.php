<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Command;

use Composer\Semver\VersionParser;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use Rector\Core\PhpParser\Parser\Parser;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Ssch\TYPO3Rector\ComposerPackages\ComposerConfigurationPathResolver;
use Ssch\TYPO3Rector\ComposerPackages\PackageParser;
use Ssch\TYPO3Rector\ComposerPackages\PackageResolver;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddPackageVersionRector;
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
     * @var VersionParser
     */
    private $versionParser;

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

    public function __construct(
        PackageResolver $packageResolver,
        VersionParser $versionParser,
        Parser $parser,
        ComposerConfigurationPathResolver $composerConfigurationPathResolver,
        SmartFileSystem $smartFileSystem,
        BetterStandardPrinter $betterStandardPrinter,
        AddPackageVersionRector $addPackageVersionRector
    ) {
        parent::__construct();

        $this->packageResolver = $packageResolver;
        $this->versionParser = $versionParser;
        $this->parser = $parser;
        $this->composerConfigurationPathResolver = $composerConfigurationPathResolver;
        $this->smartFileSystem = $smartFileSystem;
        $this->betterStandardPrinter = $betterStandardPrinter;
        $this->addPackageVersionRector = $addPackageVersionRector;
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setAliases(['typo3-extensions']);
        $this->setDescription('[DEV] Add TYPO3 extensions from packagist.org to composer configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $packages = $this->packageResolver->findAllPackagesByType('typo3-cms-extension');

        $progressBar = new ProgressBar($output, count($packages));

        foreach ($packages as $package) {
            $collection = $this->packageResolver->findPackage($package);

            $progressBar->advance();

            if (0 === count($collection)) {
                continue;
            }

            foreach (PackageParser::TYPO3_UPPER_BOUNDS as $version) {
                $typo3Version = new Typo3Version($version);
                $extension = $collection->findHighestVersion($typo3Version);

                if ($extension instanceof ExtensionVersion) {
                    $smartFileInfo = $this->composerConfigurationPathResolver->resolveByTypo3Version($typo3Version);

                    if (null === $smartFileInfo) {
                        continue;
                    }

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
}
