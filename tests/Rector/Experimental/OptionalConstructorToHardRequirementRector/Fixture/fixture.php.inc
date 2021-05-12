<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\OptionalConstructorToHardRequirement\Fixture;


use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Fluid\View\StandaloneView;

class MyClass
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var StandaloneView
     */
    private $view;

    /**
     * @var BackendUtility
     */
    private $backendUtility;

    public function __construct(Dispatcher $dispatcher = null, StandaloneView $view = null, BackendUtility $backendUtility = null, string $test = null)
    {
        $this->dispatcher = $dispatcher ?? GeneralUtility::makeInstance(ObjectManager::class)->get(Dispatcher::class);
        $this->view = $view ?? GeneralUtility::makeInstance(StandaloneView::class);
        $this->backendUtility = $backendUtility ?? GeneralUtility::makeInstance(BackendUtility::class);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\OptionalConstructorToHardRequirement\Fixture;


use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Fluid\View\StandaloneView;

class MyClass
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var StandaloneView
     */
    private $view;

    /**
     * @var BackendUtility
     */
    private $backendUtility;

    public function __construct(Dispatcher $dispatcher, StandaloneView $view, BackendUtility $backendUtility, string $test = null)
    {
        $this->dispatcher = $dispatcher;
        $this->view = $view;
        $this->backendUtility = $backendUtility;
    }
}

?>
