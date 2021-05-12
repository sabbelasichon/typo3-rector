<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

final class SomeDontValidationClass
{
    /**
     * @var string
     */
    private $someProperty;

    /**
     * @dontvalidate $param
     */
    public function someMethod($param): void
    {
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Annotation\Fixture;

final class SomeDontValidationClass
{
    /**
     * @var string
     */
    private $someProperty;

    /**
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("param")
     */
    public function someMethod($param): void
    {
    }
}

?>
