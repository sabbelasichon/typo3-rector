<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v4\ObjectManagerGetToConstructorInjection\Fixture;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class SomeService implements SingletonInterface
{

}

interface SomeInterface
{

}

final class SomeController extends ActionController
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    protected function someAction()
    {
        $someService = $this->objectManager->get(SomeService::class);
        $someInterface = $this->objectManager->get(SomeInterface::class);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v4\ObjectManagerGetToConstructorInjection\Fixture;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class SomeService implements SingletonInterface
{

}

interface SomeInterface
{

}

final class SomeController extends ActionController
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v10\v4\ObjectManagerGetToConstructorInjection\Fixture\SomeService
     */
    private $someService;
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\v10\v4\ObjectManagerGetToConstructorInjection\Fixture\SomeInterface
     */
    private $some;

    public function __construct(\Ssch\TYPO3Rector\Tests\Rector\v10\v4\ObjectManagerGetToConstructorInjection\Fixture\SomeService $someService, \Ssch\TYPO3Rector\Tests\Rector\v10\v4\ObjectManagerGetToConstructorInjection\Fixture\SomeInterface $some)
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->someService = $someService;
        $this->some = $some;
    }

    protected function someAction()
    {
        $someService = $this->someService;
        $someInterface = $this->some;
    }
}

?>
