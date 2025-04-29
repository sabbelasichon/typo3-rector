<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v14\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector\Source\MyCustomInterface;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class MyValidator implements ValidatorInterface, MyCustomInterface
{
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector\Fixture;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use Ssch\TYPO3Rector\Tests\Rector\v14\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector\Source\MyCustomInterface;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class MyValidator extends AbstractValidator implements MyCustomInterface
{
}
?>
