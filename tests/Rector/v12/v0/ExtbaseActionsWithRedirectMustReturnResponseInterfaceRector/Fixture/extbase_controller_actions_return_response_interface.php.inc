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

    public function imageDeleteActionReturnVoid(): void
    {
        $this->redirectToUri('foo');
    }

    /**
     * @return void
     */
    public function imageDeleteActionWithReturnVoidInAnnotation(): void
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

    protected function thisMethodIsNotReliable(): void
    {
        $url = 'https://example.com';
        $this->redirectToUri($url);
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

    public function imageDeleteActionReturnVoid(): ResponseInterface
    {
        return $this->redirectToUri('foo');
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function imageDeleteActionWithReturnVoidInAnnotation(): ResponseInterface
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

    protected function thisMethodIsNotReliable()
    {
        $url = 'https://example.com';
        return $this->redirectToUri($url);
    }
}

?>
