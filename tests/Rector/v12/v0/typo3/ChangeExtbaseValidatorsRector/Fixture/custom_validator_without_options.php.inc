<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ChangeExtbaseValidatorsRector\Fixture;

use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver\ValidatorResolver;

final class MyCustomValidatorWithoutOptions implements ValidatorInterface
{
    private ValidatorInterface $validator;
    private ValidatorResolver $validatorResolver;

    public function __construct(ValidatorInterface $validator, ValidatorResolver $validatorResolver)
    {
        $this->validator = $validator;
        $this->validatorResolver = $validatorResolver;
    }

    public function validate($value)
    {

    }

    public function getOptions(): array
    {
        return [];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ChangeExtbaseValidatorsRector\Fixture;

use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver\ValidatorResolver;

final class MyCustomValidatorWithoutOptions implements ValidatorInterface
{
    private ValidatorInterface $validator;
    private ValidatorResolver $validatorResolver;

    public function __construct(ValidatorInterface $validator, ValidatorResolver $validatorResolver)
    {
        $this->validator = $validator;
        $this->validatorResolver = $validatorResolver;
    }

    public function validate($value): Result
    {

    }

    public function getOptions(): array
    {
        return [];
    }
    public function setOptions(array $options): void
    {
    }
}

?>
