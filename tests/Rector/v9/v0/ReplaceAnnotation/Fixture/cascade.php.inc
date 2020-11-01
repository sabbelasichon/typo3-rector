<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

final class SomeOtherClass
{
    /**
     * @cascade
     */
    private $someProperty;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

final class SomeOtherClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    private $someProperty;
}

?>
