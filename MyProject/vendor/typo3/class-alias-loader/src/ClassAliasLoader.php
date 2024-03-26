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

use Composer\Autoload\ClassLoader as ComposerClassLoader;

/**
 * The main class loader that amends the composer class loader.
 * It deals with the alias maps and the case insensitive class loading if configured.
 */
class ClassAliasLoader
{
    /**
     * @var ComposerClassLoader
     */
    protected $composerClassLoader;

    /**
     * @var array
     */
    protected $aliasMap = array(
        'aliasToClassNameMapping' => array(),
        'classNameToAliasMapping' => array()
    );

    /**
     * @deprecated
     * @var bool
     */
    protected $caseSensitiveClassLoading = true;

    /**
     * @param ComposerClassLoader $composerClassLoader
     */
    public function __construct(ComposerClassLoader $composerClassLoader)
    {
        $this->composerClassLoader = $composerClassLoader;
    }

    /**
     * Set the alias map
     *
     * @param array $aliasMap
     */
    public function setAliasMap(array $aliasMap)
    {
        $this->aliasMap = $aliasMap;
    }

    /**
     * @deprecated
     * @param bool $caseSensitiveClassLoading
     */
    public function setCaseSensitiveClassLoading($caseSensitiveClassLoading)
    {
        $this->caseSensitiveClassLoading = $caseSensitiveClassLoading;
    }

    /**
     * Adds an alias map and merges it with already available map
     *
     * @param array $aliasMap
     */
    public function addAliasMap(array $aliasMap)
    {
        foreach ($aliasMap['aliasToClassNameMapping'] as $alias => $originalClassName) {
            $lowerCaseAlias = strtolower($alias);
            $this->aliasMap['aliasToClassNameMapping'][$lowerCaseAlias] = $originalClassName;
            $this->aliasMap['classNameToAliasMapping'][$originalClassName][$lowerCaseAlias] = $lowerCaseAlias;
        }
    }

    /**
     * Get final class name of alias
     *
     * @param string $aliasOrClassName
     * @return string
     */
    public function getClassNameForAlias($aliasOrClassName)
    {
        $lookUpClassName = strtolower($aliasOrClassName);

        return isset($this->aliasMap['aliasToClassNameMapping'][$lookUpClassName]) ? $this->aliasMap['aliasToClassNameMapping'][$lookUpClassName] : $aliasOrClassName;
    }

    /**
     * Registers this instance as an autoloader.
     *
     * @param bool $prepend Whether to prepend the autoloader or not
     */
    public function register($prepend = false)
    {
        $this->composerClassLoader->unregister();
        spl_autoload_register(array($this, 'loadClassWithAlias'), true, $prepend);
    }

    /**
     * Unregisters this instance as an autoloader.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClassWithAlias'));
    }

    /**
     * Main class loading method registered with spl_autoload_register()
     *
     * @param string $className
     * @return bool
     */
    public function loadClassWithAlias($className)
    {
        $originalClassName = $this->getOriginalClassName($className);

        return $originalClassName
            ? $this->loadOriginalClassAndSetAliases($originalClassName)
            : $this->loadClass($className);
    }

    /**
     * Load class with the option to respect case insensitivity
     * @deprecated
     *
     * @param string $className
     * @return bool|null
     */
    public function loadClass($className)
    {
        $classFound = $this->composerClassLoader->loadClass($className);
        if (!$classFound && !$this->caseSensitiveClassLoading) {
            $classFound = $this->composerClassLoader->loadClass(strtolower($className));
        }
        return $classFound;
    }

    /**
     * Looks up the original class name from the alias map
     *
     * @param string $aliasOrClassName
     * @return string|NULL NULL if no alias mapping is found or the original class name as string
     */
    protected function getOriginalClassName($aliasOrClassName)
    {
        // Is an original class which has an alias
        if (array_key_exists($aliasOrClassName, $this->aliasMap['classNameToAliasMapping'])) {
            return $this->aliasMap['classNameToAliasMapping'][$aliasOrClassName] === array()
                ? null
                : $aliasOrClassName
                ;
        }
        // Is an alias (we're graceful ignoring casing for alias definitions)
        $lowerCasedClassName = strtolower($aliasOrClassName);
        if (array_key_exists($lowerCasedClassName, $this->aliasMap['aliasToClassNameMapping'])) {
            return $this->aliasMap['aliasToClassNameMapping'][$lowerCasedClassName];
        }
        // No alias registered for this class name, return and remember that info
        $this->aliasMap['classNameToAliasMapping'][$aliasOrClassName] = array();
        return null;
    }

    /**
     * Load classes and set aliases.
     * The class_exists calls are safety guards to avoid fatals when
     * class files were included or aliases were set manually in userland code.
     *
     * @param string $originalClassName
     * @return bool|null
     */
    protected function loadOriginalClassAndSetAliases($originalClassName)
    {
        if ($this->classOrInterfaceExists($originalClassName) || $this->loadClass($originalClassName)) {
            foreach ($this->aliasMap['classNameToAliasMapping'][$originalClassName] as $aliasClassName) {
                if (!$this->classOrInterfaceExists($aliasClassName)) {
                    class_alias($originalClassName, $aliasClassName);
                }
            }

            return true;
        }

        return null;
    }

    /**
     * @param string $className
     * @return bool
     */
    protected function classOrInterfaceExists($className)
    {
        $classOrInterfaceExists = class_exists($className, false) || interface_exists($className, false);
        if ($classOrInterfaceExists) {
            return true;
        }
        if (function_exists('trait_exists')) {
            return trait_exists($className, false);
        }

        return false;
    }
}
