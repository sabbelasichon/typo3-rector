<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Compiler;

use TYPO3Fluid\Fluid\Core\Parser\ParsedTemplateInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;

/**
 * Abstract Fluid Compiled template.
 *
 * @internal
 */
abstract class AbstractCompiledTemplate implements ParsedTemplateInterface
{
    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        // void, ignored.
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return static::class;
    }

    /**
     * Returns a variable container used in the PostParse Facet.
     *
     * @return VariableProviderInterface
     */
    public function getVariableContainer()
    {
        return new StandardVariableProvider();
    }

    /**
     * Render the parsed template with rendering context
     *
     * @param RenderingContextInterface $renderingContext The rendering context to use
     * @return string Rendered string
     */
    public function render(RenderingContextInterface $renderingContext)
    {
        return '';
    }

    /**
     * @return bool
     */
    public function isCompilable()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isCompiled()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function hasLayout()
    {
        return false;
    }

    /**
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public function getLayoutName(RenderingContextInterface $renderingContext)
    {
        return '';
    }

    /**
     * @param RenderingContextInterface $renderingContext
     */
    public function addCompiledNamespaces(RenderingContextInterface $renderingContext)
    {
    }
}
