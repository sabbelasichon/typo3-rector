<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\AddRequestArgumentToPageRendererMethodsRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyExtbaseController extends ActionController
{
    private PageRenderer $pageRenderer;

    public function __construct(PageRenderer $pageRenderer)
    {
        $this->pageRenderer = $pageRenderer;
    }

    public function myAction(): ResponseInterface
    {
        $this->pageRenderer->setDocType($this->docType);
        return $this->pageRenderer->renderResponse();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\AddRequestArgumentToPageRendererMethodsRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyExtbaseController extends ActionController
{
    private PageRenderer $pageRenderer;

    public function __construct(PageRenderer $pageRenderer)
    {
        $this->pageRenderer = $pageRenderer;
    }

    public function myAction(): ResponseInterface
    {
        $this->pageRenderer->setDocType($this->docType, $this->request);
        return $this->pageRenderer->renderResponse($this->request);
    }
}

?>
