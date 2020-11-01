<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Fixture;

final class SomeInjectClass
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Source\InjectionClass
     * @inject
     */
    public $inject2;
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Source\InjectionClass
     * @inject
     */
    private $inject;

    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Source\InjectionClass
     * @inject
     */
    private $inject3;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotation\Source\InjectionClass;
final class SomeInjectClass
{
    /**
     * @var InjectionClass
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    public $inject2;
    /**
     * @var InjectionClass
     */
    private $inject;

    /**
     * @var InjectionClass
     */
    private $inject3;

    public function injectInject(InjectionClass $inject): void
    {
        $this->inject = $inject;
    }

    public function injectInject3(InjectionClass $inject3): void
    {
        $this->inject3 = $inject3;
    }
}

?>
