<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;

final class SomeValidateClass
{
    /**
     * @validate NotEmpty, EmailAddress
     */
    private $validate1;

    /**
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("EmailAddress")
     */
    private $validate2;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;

final class SomeValidateClass
{
    /**
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("EmailAddress")
     */
    private $validate1;

    /**
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("EmailAddress")
     */
    private $validate2;
}

?>
