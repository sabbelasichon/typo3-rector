<?php

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

if (class_exists('TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper')) {
    return;
}

class AbstractViewHelper
{
    /**
     * @var array<string, mixed>
     */
    protected $arguments = [];

    /**
     * @var RenderingContextInterface
     */
    protected $renderingContext;

    public function initialize()
    {
    }

    /**
     * @return void
     */
    public function initializeArguments()
    {
    }
}
