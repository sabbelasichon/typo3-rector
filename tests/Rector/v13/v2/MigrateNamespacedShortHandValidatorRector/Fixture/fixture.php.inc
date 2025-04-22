<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateNamespacedShortHandValidatorRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyClass
{
    /**
     * @Extbase\Validate("TYPO3.CMS.Extbase:NotEmpty")
     */
    protected $myProperty1;

    /**
     * @Extbase\Validate("Vendor.Extension:Custom")
     */
    protected $myProperty2;

    /**
     * @Extbase\Validate("NotEmpty")
     */
    protected $myProperty3;

    /**
     * @Extbase\Validate("\TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     */
    protected $myProperty4;

    /**
     * @var string
     * @Extbase\Validate("TYPO3.CMS.Extbase:NotEmpty")
     */
    protected $myProperty5;
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateNamespacedShortHandValidatorRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyClass
{
    /**
     * @Extbase\Validate("NotEmpty")
     */
    protected $myProperty1;

    /**
     * @Extbase\Validate("Vendor\Extension\Validation\Validator\CustomValidator")
     */
    protected $myProperty2;

    /**
     * @Extbase\Validate("NotEmpty")
     */
    protected $myProperty3;

    /**
     * @Extbase\Validate("\TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator")
     */
    protected $myProperty4;

    /**
     * @var string
     * @Extbase\Validate("NotEmpty")
     */
    protected $myProperty5;
}
?>
