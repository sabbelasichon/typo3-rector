<?php

namespace Ssch\TYPO3Rector\Tests\Annotation\Fixture;

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

namespace Ssch\TYPO3Rector\Tests\Annotation\Fixture;

final class SomeTransientClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Transient
     */
    private $transient;
}

?>
