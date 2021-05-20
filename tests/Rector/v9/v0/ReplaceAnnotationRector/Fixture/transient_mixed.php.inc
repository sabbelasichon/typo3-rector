<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceAnnotationRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;

final class SomeTransientClass
{
    /**
     * @transient
     */
    private $transient;

    /**
     * @Extbase\ORM\Transient
     */
    private $transient2;
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

    /**
     * @Extbase\ORM\Transient
     */
    private $transient2;
}

?>
