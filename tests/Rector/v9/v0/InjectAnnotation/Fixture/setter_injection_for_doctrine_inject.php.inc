<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;

final class SomeInjectClassWithDoctrineAnnotations
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Source\InjectionClass
     * @Extbase\Inject
     */
    public $inject;

    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Source\InjectionClass
     * @Extbase\Inject
     */
    protected $inject2;

    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Source\InjectionClass
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $inject3;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Source\InjectionClass;
use TYPO3\CMS\Extbase\Annotation as Extbase;

final class SomeInjectClassWithDoctrineAnnotations
{
    /**
     * @var InjectionClass
     * @Extbase\Inject
     */
    public $inject;

    /**
     * @var InjectionClass
     */
    protected $inject2;

    /**
     * @var InjectionClass
     */
    protected $inject3;

    public function injectInject2(InjectionClass $inject2): void
    {
        $this->inject2 = $inject2;
    }

    public function injectInject3(InjectionClass $inject3): void
    {
        $this->inject3 = $inject3;
    }
}

?>
