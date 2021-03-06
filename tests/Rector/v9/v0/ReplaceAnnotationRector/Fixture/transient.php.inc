<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceAnnotationRector\Fixture;

final class SomeTransientClass
{
    /**
     * @transient
     */
    private $transient;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceAnnotationRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;
final class SomeTransientClass
{
    /**
     * @Extbase\ORM\Transient
     */
    private $transient;
}

?>
