<?php

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

namespace TYPO3\CMS\Extbase\Property;

/**
 * Concrete configuration object for the PropertyMapper.
 */
class PropertyMappingConfiguration implements PropertyMappingConfigurationInterface
{
    /**
     * Placeholder in property paths for multi-valued types
     */
    public const PROPERTY_PATH_PLACEHOLDER = '*';

    /**
     * multi-dimensional array which stores type-converter specific configuration:
     * 1. Dimension: Fully qualified class name of the type converter
     * 2. Dimension: Configuration Key
     * Value: Configuration Value
     *
     * @var array
     */
    protected $configuration;

    /**
     * Stores the configuration for specific child properties.
     *
     * @var \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface[]
     */
    protected $subConfigurationForProperty = [];

    /**
     * Keys which should be renamed
     *
     * @var array
     */
    protected $mapping = [];

    /**
     * @var \TYPO3\CMS\Extbase\Property\TypeConverterInterface
     */
    protected $typeConverter;

    /**
     * List of allowed property names to be converted
     *
     * @var array
     */
    protected $propertiesToBeMapped = [];

    /**
     * List of property names to be skipped during property mapping
     *
     * @var array
     */
    protected $propertiesToSkip = [];

    /**
     * List of disallowed property names which will be ignored while property mapping
     *
     * @var array
     */
    protected $propertiesNotToBeMapped = [];

    /**
     * If TRUE, unknown properties will be skipped during property mapping
     *
     * @var bool
     */
    protected $skipUnknownProperties = false;

    /**
     * If TRUE, unknown properties will be mapped.
     *
     * @var bool
     */
    protected $mapUnknownProperties = false;

    /**
     * The behavior is as follows:
     *
     * - if a property has been explicitly forbidden using allowAllPropertiesExcept(...), it is directly rejected
     * - if a property has been allowed using allowProperties(...), it is directly allowed.
     * - if allowAllProperties* has been called, we allow unknown properties
     * - else, return FALSE.
     *
     * @param string $propertyName
     * @return bool TRUE if the given propertyName should be mapped, FALSE otherwise.
     */
    public function shouldMap($propertyName)
    {
        if (isset($this->propertiesNotToBeMapped[$propertyName])) {
            return false;
        }

        if (isset($this->propertiesToBeMapped[$propertyName])) {
            return true;
        }

        if (isset($this->subConfigurationForProperty[self::PROPERTY_PATH_PLACEHOLDER])) {
            return true;
        }

        return $this->mapUnknownProperties;
    }

    /**
     * Check if the given $propertyName should be skipped during mapping.
     *
     * @param string $propertyName
     * @return bool
     */
    public function shouldSkip($propertyName)
    {
        return isset($this->propertiesToSkip[$propertyName]);
    }

    /**
     * Allow all properties in property mapping, even unknown ones.
     *
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration this
     */
    public function allowAllProperties()
    {
        $this->mapUnknownProperties = true;
        return $this;
    }

    /**
     * Allow a list of specific properties. All arguments of
     * allowProperties are used here (varargs).
     *
     * Example: allowProperties('title', 'content', 'author')
     *
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration
     */
    public function allowProperties()
    {
        foreach (func_get_args() as $propertyName) {
            $this->propertiesToBeMapped[$propertyName] = $propertyName;
        }
        return $this;
    }

    /**
     * Skip a list of specific properties. All arguments of
     * skipProperties are used here (varargs).
     *
     * Example: skipProperties('unused', 'dummy')
     *
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration this
     */
    public function skipProperties()
    {
        foreach (func_get_args() as $propertyName) {
            $this->propertiesToSkip[$propertyName] = $propertyName;
        }
        return $this;
    }

    /**
     * Allow all properties during property mapping, but reject a few
     * selected ones (blacklist).
     *
     * Example: allowAllPropertiesExcept('password', 'userGroup')
     *
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration this
     */
    public function allowAllPropertiesExcept()
    {
        $this->mapUnknownProperties = true;

        foreach (func_get_args() as $propertyName) {
            $this->propertiesNotToBeMapped[$propertyName] = $propertyName;
        }
        return $this;
    }

    /**
     * When this is enabled, properties that are disallowed will be skipped
     * instead of triggering an error during mapping.
     *
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration this
     */
    public function skipUnknownProperties()
    {
        $this->skipUnknownProperties = true;
        return $this;
    }

    /**
     * Whether unknown (unconfigured) properties should be skipped during
     * mapping, instead if causing an error.
     *
     * @return bool
     */
    public function shouldSkipUnknownProperties()
    {
        return $this->skipUnknownProperties;
    }

    /**
     * Returns the sub-configuration for the passed $propertyName. Must ALWAYS return a valid configuration object!
     *
     * @param string $propertyName
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface the property mapping configuration for the given $propertyName.
     */
    public function getConfigurationFor($propertyName)
    {
        if (isset($this->subConfigurationForProperty[$propertyName])) {
            return $this->subConfigurationForProperty[$propertyName];
        }
        if (isset($this->subConfigurationForProperty[self::PROPERTY_PATH_PLACEHOLDER])) {
            return $this->subConfigurationForProperty[self::PROPERTY_PATH_PLACEHOLDER];
        }

        return new self();
    }

    /**
     * Maps the given $sourcePropertyName to a target property name.
     *
     * @param string $sourcePropertyName
     * @return string property name of target
     */
    public function getTargetPropertyName($sourcePropertyName)
    {
        if (isset($this->mapping[$sourcePropertyName])) {
            return $this->mapping[$sourcePropertyName];
        }
        return $sourcePropertyName;
    }

    /**
     * @param string $typeConverterClassName
     * @param string $key
     * @return mixed configuration value for the specific $typeConverterClassName. Can be used by Type Converters to fetch converter-specific configuration.
     */
    public function getConfigurationValue($typeConverterClassName, $key)
    {
        if (!isset($this->configuration[$typeConverterClassName][$key])) {
            return null;
        }

        return $this->configuration[$typeConverterClassName][$key];
    }

    /**
     * Define renaming from Source to Target property.
     *
     * @param string $sourcePropertyName
     * @param string $targetPropertyName
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration this
     */
    public function setMapping($sourcePropertyName, $targetPropertyName)
    {
        $this->mapping[$sourcePropertyName] = $targetPropertyName;
        return $this;
    }

    /**
     * Set all options for the given $typeConverter.
     *
     * @param string $typeConverter class name of type converter
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration this
     */
    public function setTypeConverterOptions($typeConverter, array $options)
    {
        foreach ($this->getTypeConvertersWithParentClasses($typeConverter) as $typeConverter) {
            $this->configuration[$typeConverter] = $options;
        }
        return $this;
    }

    /**
     * Set a single option (denoted by $optionKey) for the given $typeConverter.
     *
     * @param string $typeConverter class name of type converter
     * @param string $optionKey
     * @param mixed $optionValue
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration this
     */
    public function setTypeConverterOption($typeConverter, $optionKey, $optionValue)
    {
        foreach ($this->getTypeConvertersWithParentClasses($typeConverter) as $typeConverter) {
            $this->configuration[$typeConverter][$optionKey] = $optionValue;
        }
        return $this;
    }

    /**
     * Get type converter classes including parents for the given type converter
     *
     * When setting an option on a subclassed type converter, this option must also be set on
     * all its parent type converters.
     *
     * @param string $typeConverter The type converter class
     * @return array Class names of type converters
     */
    protected function getTypeConvertersWithParentClasses($typeConverter)
    {
        $typeConverterClasses = class_parents($typeConverter);
        $typeConverterClasses = $typeConverterClasses ?: [];
        $typeConverterClasses[] = $typeConverter;
        return $typeConverterClasses;
    }

    /**
     * Returns the configuration for the specific property path, ready to be modified. Should be used
     * inside a fluent interface like:
     * $configuration->forProperty('foo.bar')->setTypeConverterOption(....)
     *
     * @param string $propertyPath
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration (or a subclass thereof)
     */
    public function forProperty($propertyPath)
    {
        $splittedPropertyPath = explode('.', $propertyPath);
        return $this->traverseProperties($splittedPropertyPath);
    }

    /**
     * Traverse the property configuration. Only used by forProperty().
     *
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration (or a subclass thereof)
     */
    public function traverseProperties(array $splittedPropertyPath)
    {
        if (empty($splittedPropertyPath)) {
            return $this;
        }

        $currentProperty = array_shift($splittedPropertyPath);
        if (!isset($this->subConfigurationForProperty[$currentProperty])) {
            $type = static::class;
            if (isset($this->subConfigurationForProperty[self::PROPERTY_PATH_PLACEHOLDER])) {
                $this->subConfigurationForProperty[$currentProperty] = clone $this->subConfigurationForProperty[self::PROPERTY_PATH_PLACEHOLDER];
            } else {
                $this->subConfigurationForProperty[$currentProperty] = new $type();
            }
        }
        return $this->subConfigurationForProperty[$currentProperty]->traverseProperties($splittedPropertyPath);
    }

    /**
     * Return the type converter set for this configuration.
     *
     * @return \TYPO3\CMS\Extbase\Property\TypeConverterInterface|null
     */
    public function getTypeConverter()
    {
        return $this->typeConverter;
    }

    /**
     * Set a type converter which should be used for this specific conversion.
     *
     * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration this
     */
    public function setTypeConverter(TypeConverterInterface $typeConverter)
    {
        $this->typeConverter = $typeConverter;
        return $this;
    }
}
