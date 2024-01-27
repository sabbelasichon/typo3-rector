<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\SubstituteBackendTemplateViewWithModuleTemplateRector\Fixture;

use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class MyControllerNoChange extends ActionController
{
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * @var BackendTemplateView
     */
    protected $view;

    /**
     * @param ViewInterface $view
     */
    protected function initializeView(ViewInterface $view)
    {
        parent::initializeView($view);
        $view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation([]);

        $pageRenderer = $this->view->getModuleTemplate()->getPageRenderer();
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/DateTimePicker');
        $view->assign('foo', 'bar');
    }

    public function initializeAction()
    {
        // some code
    }

    public function initializeIndexAction()
    {
        // some code
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\SubstituteBackendTemplateViewWithModuleTemplateRector\Fixture;

use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class MyControllerNoChange extends ActionController
{
    private ModuleTemplateFactory $moduleTemplateFactory;

    public function __construct(ModuleTemplateFactory $moduleTemplateFactory)
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    /**
     * @param ViewInterface $view
     */
    protected function initializeView(ViewInterface $view)
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        parent::initializeView($view);
        $moduleTemplate->getDocHeaderComponent()->setMetaInformation([]);

        $pageRenderer = $moduleTemplate->getPageRenderer();
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/DateTimePicker');
        $view->assign('foo', 'bar');
    }

    public function initializeAction()
    {
        // some code
    }

    public function initializeIndexAction()
    {
        // some code
    }
}

?>
