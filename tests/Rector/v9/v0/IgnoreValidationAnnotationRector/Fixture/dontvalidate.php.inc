<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\IgnoreValidationAnnotationRector\Fixture;

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

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\IgnoreValidationAnnotationRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;
final class SomeDontValidationClass
{
    /**
     * @var string
     */
    private $someProperty;

    /**
     * @Extbase\IgnoreValidation("param")
     */
    public function someMethod($param): void
    {
    }
}

?>
