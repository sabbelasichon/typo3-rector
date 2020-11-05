<?php
declare(strict_types=1);

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

if (class_exists(AbstractViewHelper::class)) {
    return;
}

class AbstractViewHelper
{
    /**
     * @var RenderingContextInterface
     */
    protected $renderingContext;

    public function initializeArguments(): void
    {

    }
}
