<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\UseTYPO3CoreViewInterfaceInExtbaseRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Fluid\Fluid\Core\Cache\SimpleFileCache;

class MyController extends ActionController
{
    public function myAction(): ResponseInterface
    {
        // setTemplate
        $this->view->setTemplate('MyTemplate');

        // initializeRenderingContext
        $this->view->initializeRenderingContext();

        // setCache
        $cache = new SimpleFileCache();
        $this->view->setCache($cache);

        // getTemplatePaths
        $templatePaths = $this->view->getTemplatePaths();

        // getViewHelperResolver
        $viewHelperResolver = $this->view->getViewHelperResolver();

        // setTemplatePathAndFilename
        $this->view->setTemplatePathAndFilename('path/to/template.html');

        // setTemplateRootPaths
        $this->view->setTemplateRootPaths(['path/to/templates/']);

        // getTemplateRootPaths
        $rootPaths = $this->view->getTemplateRootPaths();

        // setPartialRootPaths
        $this->view->setPartialRootPaths(['path/to/partials/']);

        // getPartialRootPaths
        $partialPaths = $this->view->getPartialRootPaths();

        // getLayoutRootPaths
        $layoutPaths = $this->view->getLayoutRootPaths();

        // setLayoutRootPaths
        $this->view->setLayoutRootPaths(['path/to/layouts/']);

        // setLayoutPathAndFilename
        $this->view->setLayoutPathAndFilename('path/to/layout.html');

        // setFormat is too complicated

        // setRequest
        $this->view->setRequest($this->request);

        // setTemplateSource
        $this->view->setTemplateSource('<f:render section="Main" />');

        // hasTemplate is too complicated

        return $this->responseFactory->createResponse(200);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\UseTYPO3CoreViewInterfaceInExtbaseRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Fluid\Fluid\Core\Cache\SimpleFileCache;

class MyController extends ActionController
{
    public function myAction(): ResponseInterface
    {
        // setTemplate
        $this->view->getRenderingContext()->setControllerAction('MyTemplate');

        // initializeRenderingContext
        $this->view->getRenderingContext()->getViewHelperVariableContainer()->setView($this->view);

        // setCache
        $cache = new SimpleFileCache();
        $this->view->getRenderingContext()->setCache($cache);

        // getTemplatePaths
        $templatePaths = $this->view->getRenderingContext()->getTemplatePaths();

        // getViewHelperResolver
        $viewHelperResolver = $this->view->getRenderingContext()->getViewHelperResolver();

        // setTemplatePathAndFilename
        $this->view->getRenderingContext()->getTemplatePaths()->setTemplatePathAndFilename('path/to/template.html');

        // setTemplateRootPaths
        $this->view->getRenderingContext()->getTemplatePaths()->setTemplateRootPaths(['path/to/templates/']);

        // getTemplateRootPaths
        $rootPaths = $this->view->getRenderingContext()->getTemplatePaths()->getTemplateRootPaths();

        // setPartialRootPaths
        $this->view->getRenderingContext()->getTemplatePaths()->setPartialRootPaths(['path/to/partials/']);

        // getPartialRootPaths
        $partialPaths = $this->view->getRenderingContext()->getTemplatePaths()->getPartialRootPaths();

        // getLayoutRootPaths
        $layoutPaths = $this->view->getRenderingContext()->getTemplatePaths()->getLayoutRootPaths();

        // setLayoutRootPaths
        $this->view->getRenderingContext()->getTemplatePaths()->setLayoutRootPaths(['path/to/layouts/']);

        // setLayoutPathAndFilename
        $this->view->getRenderingContext()->getTemplatePaths()->setLayoutPathAndFilename('path/to/layout.html');

        // setFormat is too complicated

        // setRequest
        $this->view->getRenderingContext()->setAttribute(ServerRequestInterface::class, $this->request);

        // setTemplateSource
        $this->view->getRenderingContext()->getTemplatePaths()->setTemplateSource('<f:render section="Main" />');

        // hasTemplate is too complicated

        return $this->responseFactory->createResponse(200);
    }
}

?>
