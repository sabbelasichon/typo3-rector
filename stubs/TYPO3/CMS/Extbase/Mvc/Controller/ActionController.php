<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Mvc\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\ResponseFactoryInterface;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

if (class_exists(ActionController::class)) {
    return;
}

class ActionController extends AbstractController
{
    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * @var ViewInterface
     */
    protected $view;

    public function forward(string $actionName, string $controllerName = null, string $extensionName = null, array $arguments = null): void
    {
    }

    protected function redirect($actionName, $controllerName = null, $extensionName = null, array $arguments = null, $pageUid = null, $delay = 0, $statusCode = 303): void
    {

    }

    protected function redirectToUri($uri, $delay = 0, $statusCode = 303): void
    {

    }

    protected function htmlResponse(string $html = null): ResponseInterface
    {
        return $this->responseFactory->createHtmlResponse(
            $html ?? $this->view->render()
        );
    }
}
