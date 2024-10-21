<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\ExtbaseActionsWithRedirectMustReturnResponseInterfaceRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function handleExpiredRegistrationsAction()
    {
        $this->redirect('list');
    }

    public function imageDeleteAction()
    {
        $this->redirectToUri('foo');
    }

    /**
     * @return void
     */
    public function imageDeleteActionWithReturnVoid(): void
    {
        $this->redirectToUri('foo');
    }

    public function alreadyMigratedHandleExpiredRegistrationsAction(): ResponseInterface
    {
        return $this->redirect('list');
    }

    public function alreadyMigratedImageDeleteAction(): ResponseInterface
    {
        return $this->redirectToUri('foo');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\ExtbaseActionsWithRedirectMustReturnResponseInterfaceRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function handleExpiredRegistrationsAction(): ResponseInterface
    {
        return $this->redirect('list');
    }

    public function imageDeleteAction(): ResponseInterface
    {
        return $this->redirectToUri('foo');
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function imageDeleteActionWithReturnVoid(): ResponseInterface
    {
        return $this->redirectToUri('foo');
    }

    public function alreadyMigratedHandleExpiredRegistrationsAction(): ResponseInterface
    {
        return $this->redirect('list');
    }

    public function alreadyMigratedImageDeleteAction(): ResponseInterface
    {
        return $this->redirectToUri('foo');
    }
}

?>
