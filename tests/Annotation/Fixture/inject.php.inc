<?php

final class SomeInjectClass
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Fixture\InjectionClass
     * @inject
     */
    public $inject2;
    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Fixture\InjectionClass
     * @inject
     */
    private $inject;

    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Fixture\InjectionClass
     * @inject
     */
    private $inject3;
}

?>
-----
<?php

final class SomeInjectClass
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Fixture\InjectionClass
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    public $inject2;
    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Fixture\InjectionClass
     */
    private $inject;

    /**
     * @var \Ssch\TYPO3Rector\Tests\Annotation\Fixture\InjectionClass
     */
    private $inject3;

    public function injectInject(\Ssch\TYPO3Rector\Tests\Annotation\Fixture\InjectionClass $inject): void
    {
        $this->inject = $inject;
    }

    public function injectInject3(\Ssch\TYPO3Rector\Tests\Annotation\Fixture\InjectionClass $inject3): void
    {
        $this->inject3 = $inject3;
    }
}

?>
