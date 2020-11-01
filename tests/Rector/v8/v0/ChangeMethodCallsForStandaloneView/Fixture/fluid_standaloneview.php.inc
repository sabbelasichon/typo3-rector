<?php

use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

final class MyStandaloneView
{
    /**
     * @var StandaloneView
     */
    private $standaloneView;

    public function __construct()
    {
        $objectManager = new ObjectManager();
        $this->standaloneView = $objectManager->get(StandaloneView::class);
    }

    public function doSomething(): void
    {
        $this->standaloneView->setLayoutRootPath('MyPath');
        $this->standaloneView->setPartialRootPath('MyPath');
        $layoutRootPath = $this->standaloneView->getLayoutRootPath();
        $partialRootPath = $this->standaloneView->getPartialRootPath();
    }
}

?>
-----
<?php

use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

final class MyStandaloneView
{
    /**
     * @var StandaloneView
     */
    private $standaloneView;

    public function __construct()
    {
        $objectManager = new ObjectManager();
        $this->standaloneView = $objectManager->get(StandaloneView::class);
    }

    public function doSomething(): void
    {
        $this->standaloneView->setLayoutRootPaths(['MyPath']);
        $this->standaloneView->setPartialRootPaths(['MyPath']);
        $layoutRootPath = array_shift($this->standaloneView->getLayoutRootPaths());
        $partialRootPath = array_shift($this->standaloneView->getPartialRootPaths());
    }
}

?>
