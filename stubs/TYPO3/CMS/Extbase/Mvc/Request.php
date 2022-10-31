<?php

namespace TYPO3\CMS\Extbase\Mvc;

use Psr\Http\Message\ServerRequestInterface;

if (class_exists('TYPO3\CMS\Extbase\Mvc\Request')) {
    return;
}

class Request
{
    protected ServerRequestInterface $request;

    /**
     * @return string
     */
    public function getControllerExtensionName()
    {
        return 'extensionName';
    }

    public function getRequestUri(): string
    {
        return 'uri';
    }

    /**
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }
}
