<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ExtbaseAnnotationToAttributeRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy()
     * @TYPO3\CMS\Extbase\Annotation\ORM\Transient()
     * @Extbase\ORM\Lazy()
     */
    protected string $property = '';

    /**
     * @Extbase\ORM\Cascade("remove")
     */
    protected $cascade;

    /**
     * @Extbase\Validate("StringLength", options={"minimum": 1, "maximum": 10})
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("TYPO3.CMS.Extbase:NotEmpty")
     * @Extbase\Validate("TYPO3.CMS.Extbase.Tests.Unit.Reflection.Fixture:DummyValidator")
     * @Extbase\Validate("\TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     * @Extbase\Validate("TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     */
    protected $propertyWithValidateAnnotations;

    /**
     * @Extbase\IgnoreValidation("param")
     */
    public function method($param) {}

    /**
     * @Extbase\Validate(param="request", validator="TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     */
    public function updateObjectInformationAction(){}
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ExtbaseAnnotationToAttributeRector\Fixture;

use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;
use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyClass
{
    #[TYPO3\CMS\Extbase\Annotation\ORM\Lazy]
    #[TYPO3\CMS\Extbase\Annotation\ORM\Transient]
    #[Extbase\ORM\Lazy]
    protected string $property = '';

    #[Extbase\ORM\Cascade(['value' => 'remove'])]
    protected $cascade;

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['minimum' => 1, 'maximum' => 10]])]
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    #[Extbase\Validate(['validator' => 'TYPO3.CMS.Extbase:NotEmpty'])]
    #[Extbase\Validate(['validator' => 'TYPO3.CMS.Extbase.Tests.Unit.Reflection.Fixture:DummyValidator'])]
    #[Extbase\Validate(['validator' => NotEmptyValidator::class])]
    #[Extbase\Validate(['validator' => NotEmptyValidator::class])]
    protected $propertyWithValidateAnnotations;

    #[Extbase\IgnoreValidation(['value' => 'param'])]
    public function method($param) {}

    #[Extbase\Validate(['param' => 'request', 'validator' => NotEmptyValidator::class])]
    public function updateObjectInformationAction(){}
}

?>
