<?php

namespace Ssch\TYPO3Rector\Tests\Annotation\Fixture;

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

namespace Ssch\TYPO3Rector\Tests\Annotation\Fixture;

final class SomeLazyClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    private $someProperty;
}

?>
