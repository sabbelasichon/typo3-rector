<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\AnnotationsToAttributes\Fixture;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyEntity extends AbstractEntity
{
    /**
     * @Extbase\ORM\Lazy
     * @Extbase\ORM\Transient
     * @Extbase\ORM\Cascade("remove")
     */
    protected string $myProperty = '';

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
     * @Extbase\Validate(param="request", validator="TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     */
    public function updateObjectInformationAction(){}

    /**
     * @Extbase\IgnoreValidation("param")
     */
    public function method($param) {}

    /**
     * @Extbase\IgnoreValidation(argumentName="command")
     */
    public function activateAction($command = null) {}
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\AnnotationsToAttributes\Fixture;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\ORM\Transient;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyEntity extends AbstractEntity
{
    #[Lazy]
    #[Transient]
    #[Cascade(['value' => 'remove'])]
    protected string $myProperty = '';

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['minimum' => 1, 'maximum' => 10]])]
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    #[Extbase\Validate(['validator' => 'TYPO3.CMS.Extbase:NotEmpty'])]
    #[Extbase\Validate(['validator' => 'TYPO3.CMS.Extbase.Tests.Unit.Reflection.Fixture:DummyValidator'])]
    #[Extbase\Validate(['validator' => NotEmptyValidator::class])]
    #[Extbase\Validate(['validator' => NotEmptyValidator::class])]
    protected $propertyWithValidateAnnotations;

    #[Extbase\Validate(['param' => 'request', 'validator' => NotEmptyValidator::class])]
    public function updateObjectInformationAction(){}

    #[Extbase\IgnoreValidation(['value' => 'param'])]
    public function method($param) {}

    #[Extbase\IgnoreValidation(['argumentName' => 'command'])]
    public function activateAction($command = null) {}
}

?>
