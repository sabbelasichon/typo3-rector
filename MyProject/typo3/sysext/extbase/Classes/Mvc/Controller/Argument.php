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

namespace TYPO3\CMS\Extbase\Mvc\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

/**
 * A controller argument
 */
class Argument
{
    /**
     * @var MvcPropertyMappingConfiguration
     */
    protected $propertyMappingConfiguration;

    /**
     * Name of this argument
     *
     * @var string
     */
    protected $name = '';

    /**
     * Short name of this argument
     *
     * @var string
     */
    protected $shortName;

    /**
     * Data type of this argument's value
     *
     * @var string
     */
    protected $dataType;

    /**
     * TRUE if this argument is required
     *
     * @var bool
     */
    protected $isRequired = false;

    /**
     * Actual value of this argument
     *
     * @var mixed|null
     */
    protected $value;

    /**
     * Default value. Used if argument is optional.
     *
     * @var mixed
     */
    protected $defaultValue;

    /**
     * A custom validator, used supplementary to the base validation
     *
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * The validation results. This can be asked if the argument has errors.
     *
     * @var Result
     */
    protected $validationResults;

    /**
     * @var bool
     */
    private $hasBeenValidated = false;

    /**
     * Constructs this controller argument
     *
     * @param string $name Name of this argument
     * @param string $dataType The data type of this argument
     * @throws \InvalidArgumentException if $name is not a string or empty
     */
    public function __construct($name, $dataType)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name must be of type string, ' . gettype($name) . ' given.', 1187951688);
        }
        if ($name === '') {
            throw new \InvalidArgumentException('$name must be a non-empty string.', 1232551853);
        }
        $this->name = $name;
        $this->dataType = TypeHandlingUtility::normalizeType($dataType);

        $this->validationResults = new Result();
        $this->propertyMappingConfiguration = GeneralUtility::makeInstance(MvcPropertyMappingConfiguration::class);
    }

    /**
     * Returns the name of this argument
     *
     * @return string This argument's name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the short name of this argument.
     *
     * @param string $shortName A "short name" - a single character
     * @return Argument $this
     * @throws \InvalidArgumentException if $shortName is not a character
     */
    public function setShortName($shortName)
    {
        if ($shortName !== null && (!is_string($shortName) || strlen($shortName) !== 1)) {
            throw new \InvalidArgumentException('$shortName must be a single character or NULL', 1195824959);
        }
        $this->shortName = $shortName;
        return $this;
    }

    /**
     * Returns the short name of this argument
     *
     * @return string This argument's short name
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Returns the data type of this argument's value
     *
     * @return string The data type
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * Marks this argument to be required
     *
     * @param bool $required TRUE if this argument should be required
     * @return Argument $this
     */
    public function setRequired($required)
    {
        $this->isRequired = (bool)$required;
        return $this;
    }

    /**
     * Returns TRUE if this argument is required
     *
     * @return bool TRUE if this argument is required
     */
    public function isRequired()
    {
        return $this->isRequired;
    }

    /**
     * Sets the default value of the argument
     *
     * @param mixed $defaultValue Default value
     * @return Argument $this
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * Returns the default value of this argument
     *
     * @return mixed The default value
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Sets a custom validator which is used supplementary to the base validation
     *
     * @param ValidatorInterface $validator The actual validator object
     * @return Argument Returns $this (used for fluent interface)
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Returns the set validator
     *
     * @return ValidatorInterface The set validator, NULL if none was set
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Sets the value of this argument.
     *
     * @param mixed $rawValue The value of this argument
     *
     * @return Argument
     */
    public function setValue($rawValue)
    {
        $this->value = $rawValue;
        return $this;
    }

    /**
     * Returns the value of this argument
     *
     * @return mixed The value of this argument - if none was set, NULL is returned
     */
    public function getValue()
    {
        if ($this->value === null) {
            return $this->defaultValue;
        }
        return $this->value;
    }

    /**
     * Return the Property Mapping Configuration used for this argument; can be used by the initialize*action to modify the Property Mapping.
     *
     * @return MvcPropertyMappingConfiguration
     */
    public function getPropertyMappingConfiguration()
    {
        return $this->propertyMappingConfiguration;
    }

    /**
     * @return bool TRUE if the argument is valid, FALSE otherwise
     */
    public function isValid(): bool
    {
        return !$this->validate()->hasErrors();
    }

    /**
     * Returns a string representation of this argument's value
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    public function validate(): Result
    {
        if ($this->hasBeenValidated) {
            return $this->validationResults;
        }

        if ($this->validator !== null) {
            $validationMessages = $this->validator->validate($this->value);
            $this->validationResults->merge($validationMessages);
        }

        $this->hasBeenValidated = true;
        return $this->validationResults;
    }

    /**
     * @internal only to be used within Extbase, not part of TYPO3 Core API.
     */
    public function getValidationResults(): Result
    {
        return $this->validationResults;
    }
}
