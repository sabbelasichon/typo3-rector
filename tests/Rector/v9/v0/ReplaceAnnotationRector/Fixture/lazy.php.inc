<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceAnnotationRector\Fixture;

final class SomeLazyClass
{
    /**
     * @lazy
     */
    private $someProperty;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceAnnotationRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;
final class SomeLazyClass
{
    /**
     * @Extbase\ORM\Lazy
     */
    private $someProperty;
}

?>
