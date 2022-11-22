<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ChangeExtbaseValidatorsRector\Fixture;

use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

final class MyCustomValidator implements ValidatorInterface
{
    private array $options;
    private ValidatorInterface $validator;

    public function __construct(array $options, ValidatorInterface $validator)
    {
        $this->options = $options;
        $this->validator = $validator;
    }

    public function validate($value)
    {

    }

    public function getOptions(): array
    {
        return $this->options;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ChangeExtbaseValidatorsRector\Fixture;

use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

final class MyCustomValidator implements ValidatorInterface
{
    private array $options;
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($value): Result
    {

    }

    public function getOptions(): array
    {
        return $this->options;
    }
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}

?>
