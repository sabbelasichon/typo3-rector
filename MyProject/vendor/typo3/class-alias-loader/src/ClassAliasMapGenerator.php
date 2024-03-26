<?php
namespace TYPO3\ClassAliasLoader;

/*
 * This file is part of the class alias loader package.
 *
 * (c) Helmut Hummel <info@helhum.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use Composer\Package\PackageInterface;
use Composer\Util\Filesystem;
use TYPO3\ClassAliasLoader\Config;
use TYPO3\ClassAliasLoader\IncludeFile\CaseSensitiveToken;
use TYPO3\ClassAliasLoader\IncludeFile\PrependToken;

/**
 * This class loops over all packages that are installed by composer and
 * looks for configured class alias maps (in composer.json).
 * If at least one is found, the vendor/autoload.php file is rewritten to amend the composer class loader.
 * Otherwise it does nothing.
 */
class ClassAliasMapGenerator
{
    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function __construct(Composer $composer, IOInterface $io = null, $config = null)
    {
        $this->composer = $composer;
        $this->io = $io ?: new NullIO();
        if (\is_bool($config)) {
            // Happens during upgrade from older versions, so try to be graceful
            $config = new Config($this->composer->getPackage());
        }
        $this->config = $config ?: new Config($this->composer->getPackage());
    }

    /**
     * @deprecated
     * @throws \Exception
     */
    public function generateAliasMap()
    {
        // Is called during upgrade from older plugin versions, so try to be graceful, but output verbose message
        $this->io->writeError('<error> ┌─────────────────────────────────────────────────────────────┐ </error>');
        $this->io->writeError('<error> │ Upgraded typo3/class-alias-loader from older plugin version.│ </error>');
        $this->io->writeError('<error> │ Please run "composer dumpautoload" to complete the upgrade. │ </error>');
        $this->io->writeError('<error> └─────────────────────────────────────────────────────────────┘ </error>');
    }

    /**
     * @throws \Exception
     * @return bool
     */
    public function generateAliasMapFiles()
    {
        $config = $this->composer->getConfig();

        $filesystem = new Filesystem();
        $basePath = $filesystem->normalizePath(substr($config->get('vendor-dir'), 0, -strlen($config->get('vendor-dir', $config::RELATIVE_PATHS))));
        $vendorPath = $config->get('vendor-dir');
        $targetDir = $vendorPath . '/composer';
        $filesystem->ensureDirectoryExists($targetDir);

        $mainPackage = $this->composer->getPackage();
        $autoLoadGenerator = $this->composer->getAutoloadGenerator();
        $localRepo = $this->composer->getRepositoryManager()->getLocalRepository();
        $packageMap = $autoLoadGenerator->buildPackageMap($this->composer->getInstallationManager(), $mainPackage, $localRepo->getCanonicalPackages());

        $aliasToClassNameMapping = array();
        $classNameToAliasMapping = array();
        $classAliasMappingFound = false;

        foreach ($packageMap as $item) {
            /** @var PackageInterface $package */
            list($package, $installPath) = $item;
            $aliasLoaderConfig = new Config($package, $this->io);
            if ($aliasLoaderConfig->get('class-alias-maps') !== null) {
                if (!is_array($aliasLoaderConfig->get('class-alias-maps'))) {
                    throw new \Exception('Configuration option "class-alias-maps" must be an array');
                }
                foreach ($aliasLoaderConfig->get('class-alias-maps') as $mapFile) {
                    $mapFilePath = ($installPath ?: $basePath) . '/' . $filesystem->normalizePath($mapFile);
                    if (!is_file($mapFilePath)) {
                        $this->io->writeError(sprintf('The class alias map file "%s" configured in package "%s" was not found!', $mapFile, $package->getName()));
                        continue;
                    }
                    $packageAliasMap = require $mapFilePath;
                    if (!is_array($packageAliasMap)) {
                        throw new \Exception('Class alias map files must return an array', 1422625075);
                    }
                    if (!empty($packageAliasMap)) {
                        $classAliasMappingFound = true;
                    }
                    foreach ($packageAliasMap as $aliasClassName => $className) {
                        $lowerCasedAliasClassName = strtolower($aliasClassName);
                        $aliasToClassNameMapping[$lowerCasedAliasClassName] = $className;
                        $classNameToAliasMapping[$className][$lowerCasedAliasClassName] = $lowerCasedAliasClassName;
                    }
                }
            }
        }

        $alwaysAddAliasLoader = $this->config->get('always-add-alias-loader');
        $caseSensitiveClassLoading = $this->config->get('autoload-case-sensitivity');

        if (!$alwaysAddAliasLoader && !$classAliasMappingFound && $caseSensitiveClassLoading) {
            // No mapping found in any package and no insensitive class loading active. We return early and skip rewriting
            // Unless user configured alias loader to be always added
            return false;
        }

        $includeFile = new IncludeFile(
            $this->io,
            $this->composer,
            array(
                new CaseSensitiveToken(
                    $this->io,
                    $this->config
                ),
                new PrependToken(
                    $this->io,
                    $this->composer->getConfig()
                ),
            )
        );
        $includeFile->register();

        $this->io->write('<info>Generating ' . ($classAliasMappingFound ? '' : 'empty ') . 'class alias map file</info>');
        $this->generateAliasMapFile($aliasToClassNameMapping, $classNameToAliasMapping, $targetDir);

        return true;
    }

    /**
     * @deprecated will be removed with 2.0
     * @param $optimizeAutoloadFiles
     * @return bool
     */
    public function modifyComposerGeneratedFiles($optimizeAutoloadFiles = false)
    {
        $caseSensitiveClassLoading = $this->config->get('autoload-case-sensitivity');
        $vendorPath = $this->composer->getConfig()->get('vendor-dir');
        if (!$caseSensitiveClassLoading) {
            $this->io->writeError('<warning>Re-writing class map to support case insensitive class loading is deprecated</warning>');
            if (!$optimizeAutoloadFiles) {
                $this->io->writeError('<warning>Case insensitive class loading only works reliably if you use the optimize class loading feature of composer</warning>');
            }
            $this->rewriteClassMapWithLowerCaseClassNames($vendorPath . '/composer');
        }

        return true;
    }

    /**
     * @param array $aliasToClassNameMapping
     * @param array $classNameToAliasMapping
     * @param string $targetDir
     */
    protected function generateAliasMapFile(array $aliasToClassNameMapping, array $classNameToAliasMapping, $targetDir)
    {
        $exportArray = array(
            'aliasToClassNameMapping' => $aliasToClassNameMapping,
            'classNameToAliasMapping' => $classNameToAliasMapping
        );

        $fileContent = '<?php' . chr(10) . 'return ';
        $fileContent .= var_export($exportArray, true);
        $fileContent .= ';';

        file_put_contents($targetDir . '/autoload_classaliasmap.php', $fileContent);
    }

    /**
     * Rewrites the class map to have lowercased keys to be able to load classes with wrong casing
     * Defaults to case sensitivity (composer loader default)
     *
     * @param string $targetDir
     */
    protected function rewriteClassMapWithLowerCaseClassNames($targetDir)
    {
        $classMapContents = file_get_contents($targetDir . '/autoload_classmap.php');
        $classMapContents = preg_replace_callback('/    \'[^\']*\' => /', function ($match) {
            return strtolower($match[0]);
        }, $classMapContents);
        file_put_contents($targetDir . '/autoload_classmap.php', $classMapContents);
    }
}
