<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceAnnotationRector\Fixture;

final class SomeCascadeRemoveClass
{
    /**
     * @cascade remove
     */
    private $someProperty;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceAnnotationRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;
final class SomeCascadeRemoveClass
{
    /**
     * @Extbase\ORM\Cascade("remove")
     */
    private $someProperty;
}

?>
