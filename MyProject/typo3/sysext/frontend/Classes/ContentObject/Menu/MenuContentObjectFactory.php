<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
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

namespace TYPO3\CMS\Frontend\ContentObject\Menu;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\Menu\Exception\NoSuchMenuTypeException;

/**
 * Factory for menu content objects. Allows overriding the default
 * types like 'TMENU' with an own implementation (only one possible)
 * and new types can be registered.
 * @internal this is only used for internal purposes and solely used for EXT:frontend and not part of TYPO3's Core API.
 */
class MenuContentObjectFactory implements SingletonInterface
{
    /**
     * Register of TypoScript keys to according render class
     */
    protected array $menuTypeToClassMapping = [
        'TMENU' => TextMenuContentObject::class,
    ];

    /**
     * Gets a typo script string like 'TMENU' and returns an object of this type
     *
     * @throws Exception\NoSuchMenuTypeException
     */
    public function getMenuObjectByType(string $type = ''): AbstractMenuContentObject
    {
        $upperCasedClassName = strtoupper($type);
        if (array_key_exists($upperCasedClassName, $this->menuTypeToClassMapping)) {
            /** @var AbstractMenuContentObject $object */
            $object = GeneralUtility::makeInstance($this->menuTypeToClassMapping[$upperCasedClassName]);
            return $object;
        }
        throw new NoSuchMenuTypeException(
            'Menu type ' . (string)$type . ' has no implementing class.',
            1363278130
        );
    }

    /**
     * Register new menu type or override existing type
     *
     * @param string $type Menu type to be used in TypoScript
     * @param string $className Class rendering the menu
     */
    public function registerMenuType(string $type, string $className)
    {
        $this->menuTypeToClassMapping[strtoupper($type)] = $className;
    }
}
