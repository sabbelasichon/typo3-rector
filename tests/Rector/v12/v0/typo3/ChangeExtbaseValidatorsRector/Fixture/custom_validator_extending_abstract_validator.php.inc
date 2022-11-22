<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ChangeExtbaseValidatorsRector\Fixture;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

final class MyCustomValidatorExtendingAbstractValidator extends AbstractValidator
{
    private ValidatorInterface $validator;

    public function __construct(array $options, ValidatorInterface $validator)
    {
        parent::__construct($options);
        $this->validator = $validator;
    }

    protected function isValid($value)
    {

    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ChangeExtbaseValidatorsRector\Fixture;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

final class MyCustomValidatorExtendingAbstractValidator extends AbstractValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    protected function isValid($value): void
    {

    }
}

?>
