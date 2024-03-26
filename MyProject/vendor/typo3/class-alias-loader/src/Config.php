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

use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use Composer\Package\PackageInterface;

/**
 * Class Config
 */
class Config
{
    /**
     * Default values
     *
     * @var array
     */
    protected $config = array(
        'class-alias-maps' => null,
        'always-add-alias-loader' => false,
        'autoload-case-sensitivity' => true
    );

    /**
    * @var IOInterface
    */
    protected $io;

    /**
     * @param PackageInterface $package
     * @param IOInterface $io
     */
    public function __construct(PackageInterface $package, IOInterface $io = null)
    {
        $this->io = $io ?: new NullIO();
        $this->setAliasLoaderConfigFromPackage($package);
    }

    /**
     * @param string $configKey
     * @return mixed
     */
    public function get($configKey)
    {
        if (empty($configKey)) {
            throw new \InvalidArgumentException('Configuration key must not be empty', 1444039407);
        }
        // Extract parts of the path
        $configKey = str_getcsv($configKey, '.');
        // Loop through each part and extract its value
        $value = $this->config;
        foreach ($configKey as $segment) {
            if (array_key_exists($segment, $value)) {
                // Replace current value with child
                $value = $value[$segment];
            } else {
                return null;
            }
        }
        return $value;
    }

    /**
     * @param PackageInterface $package
     */
    protected function setAliasLoaderConfigFromPackage(PackageInterface $package)
    {
        $extraConfig = $this->handleDeprecatedConfigurationInPackage($package);
        if (isset($extraConfig['typo3/class-alias-loader']['class-alias-maps'])) {
            $this->config['class-alias-maps'] = (array)$extraConfig['typo3/class-alias-loader']['class-alias-maps'];
        }
        if (isset($extraConfig['typo3/class-alias-loader']['always-add-alias-loader'])) {
            $this->config['always-add-alias-loader'] = (bool)$extraConfig['typo3/class-alias-loader']['always-add-alias-loader'];
        }
        if (isset($extraConfig['typo3/class-alias-loader']['autoload-case-sensitivity'])) {
            $this->config['autoload-case-sensitivity'] = (bool)$extraConfig['typo3/class-alias-loader']['autoload-case-sensitivity'];
        }
    }

    /**
     * Ensures backwards compatibility for packages which used helhum/class-alias-loader
     *
     * @param PackageInterface $package
     * @return array
     */
    protected function handleDeprecatedConfigurationInPackage(PackageInterface $package)
    {
        $extraConfig = $package->getExtra();
        $messages = array();
        if (!isset($extraConfig['typo3/class-alias-loader'])) {
            if (isset($extraConfig['helhum/class-alias-loader'])) {
                $extraConfig['typo3/class-alias-loader'] = $extraConfig['helhum/class-alias-loader'];
                $messages[] = sprintf(
                    '<warning>The package "%s" uses "helhum/class-alias-loader" section to define class alias maps, which is deprecated. Please use "typo3/class-alias-loader" instead!</warning>',
                    $package->getName()
                );
            } else {
                $extraConfig['typo3/class-alias-loader'] = array();
                if (isset($extraConfig['class-alias-maps'])) {
                    $extraConfig['typo3/class-alias-loader']['class-alias-maps'] = $extraConfig['class-alias-maps'];
                    $messages[] = sprintf(
                        '<warning>The package "%s" uses "class-alias-maps" section on top level, which is deprecated. Please move this config below the top level key "typo3/class-alias-loader" instead!</warning>',
                        $package->getName()
                    );
                }
                if (isset($extraConfig['autoload-case-sensitivity'])) {
                    $extraConfig['typo3/class-alias-loader']['autoload-case-sensitivity'] = $extraConfig['autoload-case-sensitivity'];
                    $messages[] = sprintf(
                        '<warning>The package "%s" uses "autoload-case-sensitivity" section on top level, which is deprecated. Please move this config below the top level key "typo3/class-alias-loader" instead!</warning>',
                        $package->getName()
                    );
                }
            }
        }
        if (!empty($messages)) {
            $this->io->writeError($messages);
        }
        return $extraConfig;
    }
}
