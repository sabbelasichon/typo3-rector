<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Experimental\ObjectManagerGetToConstructorInjectionRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\Experimental\ObjectManagerGetToConstructorInjectionRector\Source\MyOtherService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

final class MyController extends ActionController
{
    protected function execute()
    {
        $someService2 = $this->objectManager->get(MyOtherService::class);
        $someService = $this->objectManager->get('Ssch\TYPO3Rector\Tests\Rector\Experimental\ObjectManagerGetToConstructorInjectionRector\Source\MyOtherService');
    }

    public function initializeObject()
    {
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Experimental\ObjectManagerGetToConstructorInjectionRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\Experimental\ObjectManagerGetToConstructorInjectionRector\Source\MyOtherService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

final class MyController extends ActionController
{
    private MyOtherService $myOtherService;
    private Typo3QuerySettings $typo3QuerySettings;
    public function __construct(MyOtherService $myOtherService, Typo3QuerySettings $typo3QuerySettings)
    {
        $this->myOtherService = $myOtherService;
        parent::__construct();
        $this->typo3QuerySettings = $typo3QuerySettings;
    }
    protected function execute()
    {
        $someService2 = $this->myOtherService;
        $someService = $this->myOtherService;
    }

    public function initializeObject()
    {
        $querySettings = $this->typo3QuerySettings;
        $querySettings->setRespectStoragePage(false);
    }
}

?>
