<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ExtbaseAnnotationToAttributeRector\FixtureWithoutImportShortNames;

class MyClass
{
    /**
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $name = '';

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $cascade;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate("StringLength", options={"minimum": 1, "maximum": 10})
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     * @TYPO3\CMS\Extbase\Annotation\Validate("TYPO3.CMS.Extbase:NotEmpty")
     * @TYPO3\CMS\Extbase\Annotation\Validate("TYPO3.CMS.Extbase.Tests.Unit.Reflection.Fixture:DummyValidator")
     * @TYPO3\CMS\Extbase\Annotation\Validate("\TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     * @TYPO3\CMS\Extbase\Annotation\Validate("TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     */
    protected $propertyWithValidateAnnotations;

    /**
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("param")
     */
    public function method($param) {}

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate(param="request", validator="TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     */
    public function updateObjectInformationAction(){}
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ExtbaseAnnotationToAttributeRector\FixtureWithoutImportShortNames;

class MyClass
{
    /**
     * @var string
     */
    #[\TYPO3\CMS\Extbase\Annotation\Validate(['validator' => 'NotEmpty'])]
    protected $name = '';

    #[\TYPO3\CMS\Extbase\Annotation\ORM\Cascade(['value' => 'remove'])]
    protected $cascade;

    #[\TYPO3\CMS\Extbase\Annotation\Validate(['validator' => 'StringLength', 'options' => ['minimum' => 1, 'maximum' => 10]])]
    #[\TYPO3\CMS\Extbase\Annotation\Validate(['validator' => 'NotEmpty'])]
    #[\TYPO3\CMS\Extbase\Annotation\Validate(['validator' => 'TYPO3.CMS.Extbase:NotEmpty'])]
    #[\TYPO3\CMS\Extbase\Annotation\Validate(['validator' => 'TYPO3.CMS.Extbase.Tests.Unit.Reflection.Fixture:DummyValidator'])]
    #[\TYPO3\CMS\Extbase\Annotation\Validate(['validator' => \TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator::class])]
    #[\TYPO3\CMS\Extbase\Annotation\Validate(['validator' => \TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator::class])]
    protected $propertyWithValidateAnnotations;

    #[\TYPO3\CMS\Extbase\Annotation\IgnoreValidation(['argumentName' => 'param'])]
    public function method($param) {}

    #[\TYPO3\CMS\Extbase\Annotation\Validate(['param' => 'request', 'validator' => \TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator::class])]
    public function updateObjectInformationAction(){}
}

?>
