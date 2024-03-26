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

/**
 * This class is the only public API of this package (besides the composer.json configuration)
 * Use the only method in cases described below.
 */
class ClassAliasMap
{
    /**
     * @var ClassAliasLoader
     */
    protected static $classAliasLoader;

    /**
     * You can use this method in your code if you compare class names as strings and want to provide compatibility for that as well.
     * The impact is pretty low and boils down to a method call. In case no aliases are present in the composer installation,
     * the class name given is returned as is, because the vendor/autoload.php will not be rewritten and thus the static member of this
     * class will not be set.
     *
     * @param string $classNameOrAlias
     * @return string
     */
    public static function getClassNameForAlias($classNameOrAlias)
    {
        if (!static::$classAliasLoader) {
            return $classNameOrAlias;
        }
        return static::$classAliasLoader->getClassNameForAlias($classNameOrAlias);
    }

    /**
     * Whether or not alias maps are already registered
     *
     * @return bool
     */
    public static function hasAliasMaps()
    {
        return is_object(static::$classAliasLoader);
    }

    /**
     * Adds an alias map if the alias loader is registered, throws an exception otherwise.
     *
     * @param array $aliasMap
     * @throws \RuntimeException
     */
    public static function addAliasMap(array $aliasMap)
    {
        if (!static::$classAliasLoader) {
            throw new \RuntimeException('Cannot set an alias map as the alias loader is not registered!', 1439228111);
        }

        static::$classAliasLoader->addAliasMap($aliasMap);
    }

    /**
     * @param ClassAliasLoader $classAliasLoader
     */
    public static function setClassAliasLoader(ClassAliasLoader $classAliasLoader)
    {
        if (static::$classAliasLoader) {
            throw new \RuntimeException('Cannot set the alias loader, as it is already registered!', 1439228112);
        }
        static::$classAliasLoader = $classAliasLoader;
    }
}
