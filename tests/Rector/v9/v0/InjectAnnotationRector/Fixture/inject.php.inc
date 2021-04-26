<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotationRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotationRector\Source\ShortInjectionClass;

final class SomeInjectClass
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotationRector\Source\InjectionClass
     * @inject
     */
    public $inject2;

    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotationRector\Source\InjectionClass
     * @inject
     */
    private $inject;

    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotationRector\Source\InjectionClass
     * @inject
     */
    private $inject3;

    /**
     * @var ShortInjectionClass
     * @inject
     */
    private $inject4;

    /**
     * @var ShortInjectionClass
     * @inject
     */
    private $inject5;

    /**
     * @inject
     * @var \Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotationRector\Source\ManufacturerRepository
     */
    protected $manufacturerRepository;

    // Properties to be parsed as typolink
    protected $keysTypolinks         = array('uri', 'imageLink', 'flexLink', 'filterUrl');

    // Properties to rewrite containing storage object keys
    protected $keysObjectStorages   = array('links');

    // Properties parse as rte content
    protected $keysRteFields        = array('sublineSmall', 'sublineMedium', 'sublineLarge');

    public function injectInject5(ShortInjectionClass $inject5): void
    {
        $this->inject5 = $inject5;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotationRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotationRector\Source\InjectionClass;
use Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotationRector\Source\ManufacturerRepository;
use Ssch\TYPO3Rector\Tests\Rector\v9\v0\InjectAnnotationRector\Source\ShortInjectionClass;

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

    /**
     * @var ShortInjectionClass
     */
    private $inject4;

    /**
     * @var ShortInjectionClass
     */
    private $inject5;

    /**
     * @var ManufacturerRepository
     */
    protected $manufacturerRepository;

    // Properties to be parsed as typolink
    protected $keysTypolinks         = array('uri', 'imageLink', 'flexLink', 'filterUrl');

    // Properties to rewrite containing storage object keys
    protected $keysObjectStorages   = array('links');

    // Properties parse as rte content
    protected $keysRteFields        = array('sublineSmall', 'sublineMedium', 'sublineLarge');

    public function injectInject5(ShortInjectionClass $inject5): void
    {
        $this->inject5 = $inject5;
    }

    public function injectInject(InjectionClass $inject): void
    {
        $this->inject = $inject;
    }

    public function injectInject3(InjectionClass $inject3): void
    {
        $this->inject3 = $inject3;
    }

    public function injectInject4(ShortInjectionClass $inject4): void
    {
        $this->inject4 = $inject4;
    }


    public function injectManufacturerRepository(ManufacturerRepository $manufacturerRepository): void
    {
        $this->manufacturerRepository = $manufacturerRepository;
    }
}

?>
