<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Interface TemplateProcessorInterface
 *
 * Implemented by classes that process template
 * sources before they are handed off to the
 * TemplateParser. Allows classes to manipulate
 * the template source, the TemplateParser and
 * the ViewHelperResolver through public API.
 *
 * For example, allowing an implementer to extract
 * custom instructions from the template which are
 * then used to manipulate how ViewHelpers resolve.
 */
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
     * @param string $templateSource
     * @return string
     */
    public function preProcessSource($templateSource);
}
