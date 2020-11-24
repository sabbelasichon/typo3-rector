<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

final class SomeValidateClass
{
    /**
     * @validate \Mehrwert\Gs1CompleteOrder\Domain\Validator\PaymentTypeValidator(paymentTypes="INVOICE,CC,PPE,SB")
     */
    private $someOtherProperty;

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
     * @TYPO3\CMS\Extbase\Annotation\Validate("\Mehrwert\Gs1CompleteOrder\Domain\Validator\PaymentTypeValidator", options={"paymentTypes": "INVOICE,CC,PPE,SB"})
     */
    private $someOtherProperty;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="")
     */
    private $someOtherInvalidProperty;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     * @TYPO3\CMS\Extbase\Annotation\Validate("StringLength", options={"minimum": 0,"maximum": 255})
     */
    private $validate;
}

?>
