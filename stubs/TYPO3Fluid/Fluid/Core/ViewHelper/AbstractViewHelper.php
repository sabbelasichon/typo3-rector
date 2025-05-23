<?php

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

if (class_exists('TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper')) {
    return;
}

class AbstractViewHelper
{
    /**
     * @var RenderingContextInterface
     */
    protected $renderingContext;

    /**
     * @return void
     */
    public function initializeArguments()
    {
    }
}
