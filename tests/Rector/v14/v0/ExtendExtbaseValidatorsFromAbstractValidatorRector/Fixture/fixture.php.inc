<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector\Fixture;

use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class MyValidator implements ValidatorInterface
{
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector\Fixture;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class MyValidator extends AbstractValidator
{
}
?>
