<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\ValidateAnnotationRector\Fixture;

final class SomeControllerWithValidation
{
    /**
     * @validate $changePassword \Foo\Bar\Domain\Validator\ChangePasswordValidator
     */
    public function updateAction(\stdClass $changePassword)
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\ValidateAnnotationRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use stdClass;
final class SomeControllerWithValidation
{
    /**
     * @Extbase\Validate("\Foo\Bar\Domain\Validator\ChangePasswordValidator", param="changePassword")
     */
    public function updateAction(stdClass $changePassword)
    {
    }
}

?>
