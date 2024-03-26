<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * This ViewHelper is only meant to be used during development.
 *
 * Examples
 * ========
 *
 * Inline notation and custom title
 * --------------------------------
 *
 * ::
 *
 *     {object -> f:debug(title: 'Custom title')}
 *
 * Output::
 *
 *     all properties of {object} nicely highlighted (with custom title)
 *
 * Only output the type
 * --------------------
 *
 * ::
 *
 *     {object -> f:debug(typeOnly: true)}
 *
 * Output::
 *
 *     the type or class name of {object}
 *
 * @api
 */
class DebugViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('typeOnly', 'boolean', 'If TRUE, debugs only the type of variables', false, false);
        $this->registerArgument('levels', 'integer', 'Levels to render when rendering nested objects/arrays', false, 5);
        $this->registerArgument('html', 'boolean', 'Render HTML. If FALSE, output is indented plaintext', false, false);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $typeOnly = $arguments['typeOnly'];
        $expressionToExamine = $renderChildrenClosure();
        if ($typeOnly === true) {
            return is_object($expressionToExamine) ? get_class($expressionToExamine) : gettype($expressionToExamine);
        }

        $html = $arguments['html'];
        $levels = $arguments['levels'];
        return static::dumpVariable($expressionToExamine, $html, 1, $levels);
    }

    /**
     * @param mixed $variable
     * @param bool $html
     * @param int $level
     * @param int $levels
     * @return string
     */
    protected static function dumpVariable($variable, $html, $level, $levels)
    {
        $typeLabel = is_object($variable) ? get_class($variable) : gettype($variable);

        if (!$html) {
            if (is_scalar($variable)) {
                $string = sprintf('%s %s', $typeLabel, var_export($variable, true)) . PHP_EOL;
            } elseif (is_null($variable)) {
                $string = 'null' . PHP_EOL;
            } else {
                $string = sprintf('%s: ', $typeLabel);
                if ($level > $levels) {
                    $string .= '*Recursion limited*';
                } else {
                    $string .= PHP_EOL;
                    foreach (static::getValuesOfNonScalarVariable($variable) as $property => $value) {
                        $string .= sprintf(
                            '%s"%s": %s',
                            str_repeat('  ', $level),
                            $property,
                            static::dumpVariable($value, $html, $level + 1, $levels)
                        );
                    }
                }
            }
        } else {
            if (is_scalar($variable) || is_null($variable)) {
                $string = sprintf(
                    '<code>%s = %s</code>',
                    $typeLabel,
                    htmlspecialchars(var_export($variable, true), ENT_COMPAT, 'UTF-8', false)
                );
            } else {
                $string = sprintf('<code>%s</code>', $typeLabel);
                if ($level > $levels) {
                    $string .= '<i>Recursion limited</i>';
                } else {
                    $string .= '<ul>';
                    foreach (static::getValuesOfNonScalarVariable($variable) as $property => $value) {
                        $string .= sprintf(
                            '<li>%s: %s</li>',
                            $property,
                            static::dumpVariable($value, $html, $level + 1, $levels)
                        );
                    }
                    $string .= '</ul>';
                }
            }
        }

        return $string;
    }

    /**
     * @param mixed $variable
     * @return array
     */
    protected static function getValuesOfNonScalarVariable($variable)
    {
        if ($variable instanceof \ArrayObject || is_array($variable)) {
            return (array)$variable;
        }
        if ($variable instanceof \Iterator) {
            return iterator_to_array($variable);
        }
        if (is_resource($variable)) {
            return stream_get_meta_data($variable);
        }
        if ($variable instanceof \DateTimeInterface) {
            return [
                'class' => get_class($variable),
                'ISO8601' => $variable->format(\DateTime::ATOM),
                'UNIXTIME' => (int)$variable->format('U')
            ];
        }
        $reflection = new \ReflectionObject($variable);
        $properties = $reflection->getProperties();
        $output = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $standardVariableProvider = new StandardVariableProvider();
            $standardVariableProvider->setSource($variable);
            $output[$propertyName] = $standardVariableProvider->getByPath($propertyName);
        }
        return $output;
    }
}
