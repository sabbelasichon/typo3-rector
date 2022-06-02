<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector\Fixture;

use Exception;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    protected function proceedWithAction($registration, $actionName)
    {
    }

    public function someAction()
    {
        $this->view->assign('foo', 'bar');
    }

    public function fooAction(): ResponseInterface
    {
        $this->view->assign('foo', 'bar');
        return $this->htmlResponse();
    }

    public function initializeSomeAction()
    {
        // Do something here
    }

    protected function createDemandObjectFromSettings($settings)
    {

    }

    public function detailAction(bool $showError): ?string
    {
        if ($showError) {
            return 'Error forever';
        }

        $this->view->assign('foo', 'bar');
    }

    public function infoAction()
    {
        $response = null;
        try {
            $this->forward('foo');
        } catch (Exception $e) {
            HttpUtility::setResponseCode(HttpUtility::HTTP_STATUS_400);
        }

        return json_encode($response);
    }

    public function deleteDiagramAction()
    {
        $this->forward('diagramlist');
    }

    public function exportAction()
    {
        echo 'Export something here';
        exit();
    }

    public function handleExpiredRegistrationsAction()
    {
        $this->redirect('list');
    }

    public function imageDeleteAction()
    {
        $this->redirectToUri('foo');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector\Fixture;

use TYPO3\CMS\Extbase\Http\ForwardResponse;
use Exception;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    protected function proceedWithAction($registration, $actionName)
    {
    }

    public function someAction(): ResponseInterface
    {
        $this->view->assign('foo', 'bar');
        return $this->htmlResponse();
    }

    public function fooAction(): ResponseInterface
    {
        $this->view->assign('foo', 'bar');
        return $this->htmlResponse();
    }

    public function initializeSomeAction()
    {
        // Do something here
    }

    protected function createDemandObjectFromSettings($settings)
    {

    }

    public function detailAction(bool $showError): ResponseInterface
    {
        if ($showError) {
            return $this->htmlResponse('Error forever');
        }

        $this->view->assign('foo', 'bar');
        return $this->htmlResponse();
    }

    public function infoAction(): ResponseInterface
    {
        $response = null;
        try {
            return new ForwardResponse('foo');
        } catch (Exception $e) {
            HttpUtility::setResponseCode(HttpUtility::HTTP_STATUS_400);
        }

        return $this->jsonResponse(json_encode($response));
    }

    public function deleteDiagramAction(): ResponseInterface
    {
        return new ForwardResponse('diagramlist');
    }

    public function exportAction()
    {
        echo 'Export something here';
        exit();
    }

    public function handleExpiredRegistrationsAction()
    {
        $this->redirect('list');
    }

    public function imageDeleteAction()
    {
        $this->redirectToUri('foo');
    }
}

?>
