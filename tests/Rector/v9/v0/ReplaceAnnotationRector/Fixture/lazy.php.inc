<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

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

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

final class SomeLazyClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    private $someProperty;
}

?>
