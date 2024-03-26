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

namespace TYPO3\CMS\Extbase\Property\TypeConverter;

use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;

/**
 * Converter which transforms a simple type to a float.
 *
 * This is basically done by simply casting it.
 */
class FloatConverter extends AbstractTypeConverter
{
    /**
     * @var string
     */
    public const CONFIGURATION_THOUSANDS_SEPARATOR = 'thousandsSeparator';

    /**
     * @var string
     */
    public const CONFIGURATION_DECIMAL_POINT = 'decimalPoint';

    /**
     * @var string[]
     * @deprecated will be removed in TYPO3 v13.0, as this is defined in Services.yaml.
     */
    protected $sourceTypes = ['float', 'integer', 'string'];

    /**
     * @var string
     * @deprecated will be removed in TYPO3 v13.0, as this is defined in Services.yaml.
     */
    protected $targetType = 'float';

    /**
     * @var int
     * @deprecated will be removed in TYPO3 v13.0, as this is defined in Services.yaml.
     */
    protected $priority = 10;

    /**
     * Actually convert from $source to $targetType, by doing a typecast.
     *
     * @param mixed $source
     * @return float|\TYPO3\CMS\Extbase\Error\Error|null
     */
    public function convertFrom($source, string $targetType, array $convertedChildProperties = [], PropertyMappingConfigurationInterface $configuration = null)
    {
        if ($source === null || (string)$source === '') {
            return null;
        }
        if (is_string($source) && $configuration !== null) {
            $thousandsSeparator = $configuration->getConfigurationValue(self::class, self::CONFIGURATION_THOUSANDS_SEPARATOR);
            $decimalPoint = $configuration->getConfigurationValue(self::class, self::CONFIGURATION_DECIMAL_POINT);
            $source = str_replace([$thousandsSeparator, $decimalPoint], ['', '.'], $source);
        }
        if (!is_numeric($source)) {
            return new Error('"%s" cannot be converted to a float value.', 1332934124, [$source]);
        }
        return (float)$source;
    }
}
