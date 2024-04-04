<?php

namespace TYPO3\CMS\Extbase\Mvc\Controller;

use TYPO3\CMS\Core\Http\ResponseFactoryInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

if (class_exists('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')) {
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

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * The current request.
     *
     * @var \TYPO3\CMS\Extbase\Mvc\Request
     */
    protected $request;

    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @return void
     * @param string $actionName
     * @param string $controllerName
     * @param string $extensionName
     */
    public function forward($actionName, $controllerName = null, $extensionName = null, array $arguments = null)
    {
    }

    /**
     * @return void
     */
    protected function redirect($actionName, $controllerName = null, $extensionName = null, array $arguments = null, $pageUid = null, $delay = 0, $statusCode = 303)
    {

    }

    /**
     * @return void
     */
    protected function redirectToUri($uri, $delay = 0, $statusCode = 303)
    {

    }

    /**
     * @param string $html
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function htmlResponse($html = null)
    {
        $html = (string) $html;
        return $this->responseFactory->createHtmlResponse(
            isset($html) ? $html : $this->view->render()
        );
    }
}
