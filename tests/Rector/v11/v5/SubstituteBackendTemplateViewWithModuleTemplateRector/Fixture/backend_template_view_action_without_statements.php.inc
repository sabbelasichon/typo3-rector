<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\SubstituteBackendTemplateViewWithModuleTemplateRector\Fixture;

use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class MyControllerWithActionWithoutStatements extends ActionController
{
    protected $defaultViewObjectName = BackendTemplateView::class;

    public function myAction(): void
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\SubstituteBackendTemplateViewWithModuleTemplateRector\Fixture;

use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class MyControllerWithActionWithoutStatements extends ActionController
{
    private ModuleTemplateFactory $moduleTemplateFactory;
    public function __construct(ModuleTemplateFactory $moduleTemplateFactory)
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }
    public function myAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}

?>
