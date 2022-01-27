<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\ValidateAnnotationRector\Fixture;

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

    /**
     * @validate RegularExpression(regularExpression="/(mr|mrs)/")
     */
    protected $prop;

    /**
     * @validate RegularExpression(regularExpression='/[0-9]{4}$/')
     */
    protected $propFoo;

    /**
     * @validate RegularExpression(regularExpression="#(mr|mrs)#")
     */
    protected $prop2;

    /**
     * @var string
     * @validate RegularExpression (regularExpression = '/^[A-z0-9]{32}$/')
     */
    protected $configId;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\ValidateAnnotationRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;
final class SomeValidateClass
{
    /**
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("EmailAddress")
     */
    private $somePropertyWithMultipleValidators;

    /**
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("EmailAddress")
     */
    private $somePropertyWithMultipleValidatorsAndNoSpace;

    /**
     * @Extbase\Validate("\Custom\Extension\Domain\Validator\PaymentTypeValidator", options={"paymentTypes": "INVOICE,CC,PPE,SB"})
     */
    private $someOtherProperty;

    /**
     * @Extbase\Validate("\Custom\Extension\Domain\Validator\PaymentTypeValidator", options={"paymentTypes": "INVOICE,CC,PPE,SB"})
     * @Extbase\Validate("NotEmpty")
     */
    private $someOtherPropertyMultiple;

    /**
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("\Custom\Extension\Domain\Validator\PaymentTypeValidator", options={"paymentTypes": "INVOICE,CC,PPE,SB"})
     */
    private $someOtherProperty2Multiple;

    /**
     * @Extbase\Validate("")
     */
    private $someOtherInvalidProperty;

    /**
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("StringLength", options={"minimum": 0, "maximum": 255})
     */
    private $validate;

    /**
     * @Extbase\Validate("RegularExpression", options={"regularExpression": "/(mr|mrs)/"})
     */
    protected $prop;

    /**
     * @Extbase\Validate("RegularExpression", options={"regularExpression": "/[0-9]{4}$/"})
     */
    protected $propFoo;

    /**
     * @Extbase\Validate("RegularExpression", options={"regularExpression": "#(mr|mrs)#"})
     */
    protected $prop2;

    /**
     * @var string
     * @Extbase\Validate("RegularExpression", options={"regularExpression": "/^[A-z0-9]{32}$/"})
     */
    protected $configId;
}

?>
