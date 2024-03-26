<?php

/*
 * This file is part of the TYPO3 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Composer\Plugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use Composer\Package\RootPackageInterface;
use Composer\Util\Filesystem;

/**
 * Configuration wrapper to easily access extra configuration for installer
 */
class Config
{
    const RELATIVE_PATHS = 1;

    /**
     * @var array
     */
    public static $defaultConfig = [
        'web-dir' => 'public',
        'root-dir' => '{$web-dir}',
        'app-dir' => '{$base-dir}',
        // The following values are for internal use only and do not represent public API
        // Names and behaviour of these values might change without notice
        'composer-mode' => true,
    ];

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @param string $baseDir
     */
    public function __construct($baseDir = null)
    {
        $this->baseDir = $baseDir;
        // load defaults
        $this->config = static::$defaultConfig;
    }

    /**
     * Merges new config values with the existing ones (overriding)
     *
     * @param array $config
     */
    public function merge(array $config)
    {
        // Override defaults with given config
        if (!empty($config['typo3/cms']) && is_array($config['typo3/cms'])) {
            foreach ($config['typo3/cms'] as $key => $val) {
                $this->config[$key] = $val;
            }
        }
    }

    /**
     * Returns a setting
     *
     * @param  string $key
     * @param  int $flags Options (see class constants)
     * @return mixed
     */
    public function get($key, $flags = 0)
    {
        switch ($key) {
            case 'web-dir':
            case 'root-dir':
            case 'app-dir':
                $val = rtrim($this->process($this->config[$key], $flags), '/\\');
                return ($flags & self::RELATIVE_PATHS === 1) ? $val : $this->realpath($val);
            case 'base-dir':
                return ($flags & self::RELATIVE_PATHS === 1) ? '' : $this->realpath($this->baseDir);
            default:
                if (!isset($this->config[$key])) {
                    return null;
                }
                return $this->process($this->config[$key], $flags);
        }
    }

    /**
     * @param int $flags Options (see class constants)
     * @return array
     */
    public function all($flags = 0)
    {
        $all = [];
        foreach (array_keys($this->config) as $key) {
            $all['config'][$key] = $this->get($key, $flags);
        }

        return $all;
    }

    /**
     * @return array
     */
    public function raw()
    {
        return [
            'config' => $this->config,
        ];
    }

    /**
     * Checks whether a setting exists
     *
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Replaces {$refs} inside a config string
     *
     * @param  string $value a config string that can contain {$refs-to-other-config}
     * @param  int $flags Options (see class constants)
     * @return string
     */
    protected function process($value, $flags)
    {
        $config = $this;

        if (!is_string($value)) {
            return $value;
        }

        return preg_replace_callback(
            '#\{\$(.+)\}#',
            function ($match) use ($config, $flags) {
                return $config->get($match[1], $flags);
            },
            $value
        );
    }

    /**
     * Turns relative paths in absolute paths without realpath()
     *
     * Since the dirs might not exist yet we can not call realpath or it will fail.
     *
     * @param  string $path
     * @return string
     */
    protected function realpath($path)
    {
        if ($path === '') {
            return $this->baseDir;
        }
        if ($path[0] === '/' || (!empty($path[1]) && $path[1] === ':')) {
            return $path;
        }

        return $this->baseDir . '/' . $path;
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    /**
     * @param Composer $composer
     * @param IOInterface|null $io
     * @return Config
     */
    public static function load(Composer $composer, IOInterface $io = null)
    {
        static $config;
        if ($config === null) {
            $io = $io ?? new NullIO();
            $baseDir = static::extractBaseDir($composer->getConfig());
            $rootPackageExtraConfig = self::handleRootPackageExtraConfig($io, $composer->getPackage());
            $config = new static($baseDir);
            $config->merge($rootPackageExtraConfig);
        }
        return $config;
    }

    private static function handleRootPackageExtraConfig(IOInterface $io, RootPackageInterface $rootPackage): array
    {
        if ($rootPackage->getName() === 'typo3/cms') {
            // Configuration for the web dir is different, in case
            // typo3/cms is the root package
            self::$defaultConfig['web-dir'] = '.';

            return [];
        }
        $rootPackageExtraConfig = $rootPackage->getExtra() ?: [];
        $typo3Config = $rootPackageExtraConfig['typo3/cms'] ?? [];
        if (empty($typo3Config)) {
            return $rootPackageExtraConfig;
        }
        if (isset($rootPackageExtraConfig['typo3/cms']['app-dir'])) {
            $io->warning('Changing app-dir is not supported any more. TYPO3 application dir will always be set to Composer root directory');
        }
        if (isset($rootPackageExtraConfig['typo3/cms']['root-dir'])) {
            $io->warning('Changing root-dir is not supported any more. TYPO3 root-dir will always be the same as web-dir');
        }
        unset($rootPackageExtraConfig['typo3/cms']['root-dir'], $rootPackageExtraConfig['typo3/cms']['app-dir']);
        $fileSystem = new Filesystem();
        $config = new static('/fake/root');
        $config->merge($rootPackageExtraConfig);
        $baseDir = $fileSystem->normalizePath($config->get('base-dir'));
        $webDir = $fileSystem->normalizePath($config->get('web-dir'));
        if (!str_starts_with($webDir, $baseDir)) {
            unset($rootPackageExtraConfig['typo3/cms']['web-dir']);
            $io->writeError('<warning>TYPO3 public path must be a subdirectory of Composer root directory. Resetting web-dir config to default.</warning>');
        }

        return $rootPackageExtraConfig;
    }

    /**
     * @param \Composer\Config $config
     * @return mixed
     */
    protected static function extractBaseDir(\Composer\Config $config)
    {
        $reflectionClass = new \ReflectionClass($config);
        $reflectionProperty = $reflectionClass->getProperty('baseDir');
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue($config);
    }
}
