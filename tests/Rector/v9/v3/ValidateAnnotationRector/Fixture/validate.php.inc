<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

final class SomeValidateClass
{
    /**
     * @validate NotEmpty, EmailAddress
     */
    private $somePropertyWithMultipleValidators;

    /**
     * @validate NotEmpty,EmailAddress
     */
    private $somePropertyWithMultipleValidatorsAndNoSpace;

    /**
     * @validate \Custom\Extension\Domain\Validator\PaymentTypeValidator(paymentTypes="INVOICE,CC,PPE,SB")
     */
    private $someOtherProperty;

    /**
     * @validate \Custom\Extension\Domain\Validator\PaymentTypeValidator(paymentTypes="INVOICE,CC,PPE,SB"),NotEmpty
     */
    private $someOtherPropertyMultiple;

    /**
     * @validate NotEmpty, \Custom\Extension\Domain\Validator\PaymentTypeValidator(paymentTypes="INVOICE,CC,PPE,SB")
     */
    private $someOtherProperty2Multiple;

    /**
     * @validate
     */
    private $someOtherInvalidProperty;

    /**
     * @validate NotEmpty
     * @validate StringLength(minimum=0, maximum=255)
     */
    private $validate;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

final class SomeValidateClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="EmailAddress")
     */
    private $somePropertyWithMultipleValidators;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="EmailAddress")
     */
    private $somePropertyWithMultipleValidatorsAndNoSpace;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate("\Custom\Extension\Domain\Validator\PaymentTypeValidator", options={"paymentTypes": "INVOICE,CC,PPE,SB"})
     */
    private $someOtherProperty;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate("\Custom\Extension\Domain\Validator\PaymentTypeValidator", options={"paymentTypes": "INVOICE,CC,PPE,SB"})
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     */
    private $someOtherPropertyMultiple;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     * @TYPO3\CMS\Extbase\Annotation\Validate("\Custom\Extension\Domain\Validator\PaymentTypeValidator", options={"paymentTypes": "INVOICE,CC,PPE,SB"})
     */
    private $someOtherProperty2Multiple;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="")
     */
    private $someOtherInvalidProperty;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     * @TYPO3\CMS\Extbase\Annotation\Validate("StringLength", options={"minimum": 0, "maximum": 255})
     */
    private $validate;
}

?>
