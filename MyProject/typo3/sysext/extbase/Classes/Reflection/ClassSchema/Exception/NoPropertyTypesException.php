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

namespace TYPO3\CMS\Extbase\Reflection\ClassSchema\Exception;

/**
 * @internal only to be used within Extbase, not part of TYPO3 Core API.
 */
class NoPropertyTypesException extends \LogicException
{
    public static function create(string $className, string $propertyName): NoPropertyTypesException
    {
        return new self(
            'Property ' . $className . '::$' . $propertyName . ' does not have any types defined',
            1660215606
        );
    }
}
