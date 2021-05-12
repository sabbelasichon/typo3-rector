<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

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

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

final class SomeCascadeRemoveClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    private $someProperty;
}

?>
