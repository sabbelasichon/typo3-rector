<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrictTypesInExtbaseArgumentRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class MyArgument extends Argument
{
    protected $propertyMappingConfiguration;
    protected $name = '';
    protected $shortName;
    protected $dataType;
    protected $isRequired = false;
    protected $value;
    protected $defaultValue;
    protected $validator;
    protected $validationResults;

    public function __construct($name, $dataType)
    {
    }

    public function getName()
    {
    }

    public function setShortName($shortName)
    {
    }

    public function getShortName()
    {
    }

    public function getDataType()
    {
    }

    public function setRequired($required)
    {
    }

    public function isRequired()
    {
    }

    public function setDefaultValue($defaultValue)
    {
    }

    public function getDefaultValue()
    {
    }

    public function setValidator(ValidatorInterface $validator)
    {
    }

    public function getValidator()
    {
    }

    public function setValue($rawValue)
    {
    }

    public function getValue()
    {
    }

    public function getPropertyMappingConfiguration()
    {
    }

    public function __toString()
    {
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrictTypesInExtbaseArgumentRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class MyArgument extends Argument
{
    protected MvcPropertyMappingConfiguration $propertyMappingConfiguration;
    protected string $name = '';
    protected string $shortName = '';
    protected string $dataType = '';
    protected bool $isRequired = false;
    protected mixed $value = null;
    protected mixed $defaultValue = null;
    protected ?ValidatorInterface $validator = null;
    protected Result $validationResults;

    public function __construct(string $name, string $dataType)
    {
    }

    public function getName(): string
    {
    }

    public function setShortName(string $shortName): Argument
    {
    }

    public function getShortName(): string
    {
    }

    public function getDataType(): string
    {
    }

    public function setRequired(bool $required): Argument
    {
    }

    public function isRequired(): bool
    {
    }

    public function setDefaultValue(mixed $defaultValue): Argument
    {
    }

    public function getDefaultValue(): mixed
    {
    }

    public function setValidator(ValidatorInterface $validator): Argument
    {
    }

    public function getValidator(): ?ValidatorInterface
    {
    }

    public function setValue(mixed $rawValue): Argument
    {
    }

    public function getValue(): mixed
    {
    }

    public function getPropertyMappingConfiguration(): MvcPropertyMappingConfiguration
    {
    }

    public function __toString(): string
    {
    }
}
?>
