<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser\TemplateProcessor;

use TYPO3Fluid\Fluid\Core\Parser\Exception;
use TYPO3Fluid\Fluid\Core\Parser\TemplateProcessorInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Preprocessor to detect the "escapingEnabled" inline flag in a template.
 */
class EscapingModifierTemplateProcessor implements TemplateProcessorInterface
{
    /**
     * @var RenderingContextInterface
     */
    protected $renderingContext;

    public const SCAN_PATTERN_ESCAPINGMODIFIER = '/{(escaping|escapingEnabled)\s*=*\s*(true|false|on|off)\s*}/i';

    /**
     * @param RenderingContextInterface $renderingContext
     */
    public function setRenderingContext(RenderingContextInterface $renderingContext)
    {
        $this->renderingContext = $renderingContext;
    }

    /**
     * Pre-process the template source before it is
     * returned to the TemplateParser or passed to
     * the next TemplateProcessorInterface instance.
     *
     * @param string $templateSource
     * @return string
     */
    public function preProcessSource($templateSource)
    {
        if (strpos($templateSource, '{escaping') === false) {
            // No escaping modifier detected - early return to skip preg processing
            return $templateSource;
        }
        $matches = [];
        preg_match_all(static::SCAN_PATTERN_ESCAPINGMODIFIER, $templateSource, $matches, PREG_SET_ORDER);
        if (count($matches) > 1) {
            throw new Exception(
                'There is more than one escaping modifier defined. There can only be one {escapingEnabled=...} per template.',
                1407331080
            );
        }
        if ($matches === []) {
            return $templateSource;
        }
        if (strtolower($matches[0][2]) === 'false' || strtolower($matches[0][2]) === 'off') {
            $this->renderingContext->getTemplateParser()->setEscapingEnabled(false);
        }

        $templateSource = str_replace($matches[0][0], '', $templateSource);

        return $templateSource;
    }
}
