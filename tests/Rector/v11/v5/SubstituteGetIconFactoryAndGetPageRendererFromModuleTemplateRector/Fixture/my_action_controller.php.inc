<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;

    public function __construct(ModuleTemplateFactory $moduleTemplateFactory)
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function myAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->getPageRenderer()->loadRequireJsModule('Vendor/Extension/MyJsModule');
        $moduleTemplate->setContent($moduleTemplate->getIconFactory()->getIcon('some-icon', Icon::SIZE_SMALL)->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector\Fixture;

use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;
    private IconFactory $iconFactory;
    private PageRenderer $pageRenderer;

    public function __construct(ModuleTemplateFactory $moduleTemplateFactory, IconFactory $iconFactory, PageRenderer $pageRenderer)
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->iconFactory = $iconFactory;
        $this->pageRenderer = $pageRenderer;
    }

    public function myAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->pageRenderer->loadRequireJsModule('Vendor/Extension/MyJsModule');
        $moduleTemplate->setContent($this->iconFactory->getIcon('some-icon', Icon::SIZE_SMALL)->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}

?>
