<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

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

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

final class SomeTransientClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Transient
     */
    private $transient;
}

?>
