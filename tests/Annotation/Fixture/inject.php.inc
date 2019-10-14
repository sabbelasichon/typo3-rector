<?php

namespace Ssch\TYPO3Rector\Tests\Annotation\Fixture;

final class SomeInjectClass
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Source\InjectionClass
     * @inject
     */
    public $inject2;
    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Source\InjectionClass
     * @inject
     */
    private $inject;

    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Source\InjectionClass
     * @inject
     */
    private $inject3;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Annotation\Fixture;

final class SomeInjectClass
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Source\InjectionClass
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    public $inject2;
    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Source\InjectionClass
     */
    private $inject;

    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Source\InjectionClass
     */
    private $inject3;

    public function injectInject(\Ssch\TYPO3Rector\Tests\Annotation\Source\InjectionClass $inject): void
    {
        $this->inject = $inject;
    }

    public function injectInject3(\Ssch\TYPO3Rector\Tests\Annotation\Source\InjectionClass $inject3): void
    {
        $this->inject3 = $inject3;
    }
}

?>
