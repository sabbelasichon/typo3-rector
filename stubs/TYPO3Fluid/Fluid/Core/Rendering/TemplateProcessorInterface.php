<?php

namespace TYPO3Fluid\Fluid\Core\Parser;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

if (interface_exists('TYPO3Fluid\Fluid\Core\Parser\TemplateProcessorInterface')) {
    return;
}

interface TemplateProcessorInterface
{
    /**
     * @param RenderingContextInterface $renderingContext
     */
    public function setRenderingContext(RenderingContextInterface $renderingContext);

    /**
     * Pre-process the template source before it is
     * returned to the TemplateParser or passed to
     * the next TemplateProcessorInterface instance.
     *
     * @return string
     */
    public function preProcessSource(string $templateSource);
}
