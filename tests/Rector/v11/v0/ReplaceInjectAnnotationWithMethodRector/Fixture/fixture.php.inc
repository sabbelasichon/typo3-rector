<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector\Source\ShortInjectionClass;

final class SomeInjectClass
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector\Source\InjectionClass
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    private $inject1;

    /**
     * @var ShortInjectionClass
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    private $inject2;

    /**
     * @var ShortInjectionClass
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    private $inject3;

    /**
     * @var ShortInjectionClass
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $inject4;

    /**
     * @var ShortInjectionClass
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    public $inject5;

    public function injectInject3(ShortInjectionClass $inject3): void
    {
        $this->inject3 = $inject3;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector\Source\InjectionClass;
use Ssch\TYPO3Rector\Tests\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector\Source\ShortInjectionClass;

final class SomeInjectClass
{
    /**
     * @var InjectionClass
     */
    private $inject1;

    /**
     * @var ShortInjectionClass
     */
    private $inject2;

    /**
     * @var ShortInjectionClass
     */
    private $inject3;

    /**
     * @var ShortInjectionClass
     */
    protected $inject4;

    /**
     * @var ShortInjectionClass
     */
    public $inject5;

    public function injectInject3(ShortInjectionClass $inject3): void
    {
        $this->inject3 = $inject3;
    }

    public function injectInject1(InjectionClass $inject1): void
    {
        $this->inject1 = $inject1;
    }

    public function injectInject2(ShortInjectionClass $inject2): void
    {
        $this->inject2 = $inject2;
    }


    public function injectInject4(ShortInjectionClass $inject4): void
    {
        $this->inject4 = $inject4;
    }

    public function injectInject5(ShortInjectionClass $inject5): void
    {
        $this->inject5 = $inject5;
    }
}

?>
