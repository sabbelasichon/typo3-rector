<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector\Source\ShortInjectionClass;

final class SomeInjectClass
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector\Source\InjectionClass
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    private $inject;

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
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    private $inject;

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

    public function injectInject(InjectionClass $inject): void
    {
        $this->inject = $inject;
    }

    public function injectInject2(ShortInjectionClass $inject2): void
    {
        $this->inject2 = $inject2;
    }

    public function injectInject4(InjectionClass $inject4): void
    {
        $this->inject4 = $inject4;
    }

    public function injectInject5(InjectionClass $inject5): void
    {
        $this->inject5 = $inject5;
    }
}

?>
