<?php

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

if (class_exists('TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper')) {
    return;
}

class AbstractConditionViewHelper extends AbstractViewHelper
{
    /**
     * @param array|null $arguments
     */
    protected static function evaluateCondition($arguments = null): bool
    {
        return false;
    }

    /**
     * @param array<string, mixed> $arguments
     */
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        return isset($arguments['condition']) && (bool)($arguments['condition']);
    }
}
