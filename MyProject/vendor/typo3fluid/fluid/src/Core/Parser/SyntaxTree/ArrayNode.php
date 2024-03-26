<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser\SyntaxTree;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Array Syntax Tree Node. Handles JSON-like arrays.
 *
 * @internal
 * @todo Make class final.
 */
class ArrayNode extends AbstractNode
{
    /**
     * Constructor.
     *
     * @param array $internalArray An associative array. Each key is a string. Each value is either a literal, or an AbstractNode.
     */
    public function __construct(private readonly array $internalArray)
    {
    }

    /**
     * Evaluate the array and return an evaluated array
     *
     * @param RenderingContextInterface $renderingContext
     * @return array An associative array with literal values
     */
    public function evaluate(RenderingContextInterface $renderingContext)
    {
        $arrayToBuild = [];
        foreach ($this->internalArray as $key => $value) {
            $arrayToBuild[$key] = $value instanceof NodeInterface ? $value->evaluate($renderingContext) : $value;
        }
        return $arrayToBuild;
    }

    public function convert(TemplateCompiler $templateCompiler): array
    {
        $arrayVariableName = $templateCompiler->variableName('array');
        $accumulatedInitializationPhpCode = '';
        $initializationPhpCode = sprintf('%s = [' . chr(10), $arrayVariableName);
        foreach ($this->internalArray as $key => $value) {
            if ($value instanceof NodeInterface) {
                $converted = $value->convert($templateCompiler);
                if (!empty($converted['initialization'])) {
                    $accumulatedInitializationPhpCode .= $converted['initialization'];
                }
                $initializationPhpCode .= sprintf(
                    '\'%s\' => %s,' . chr(10),
                    $key,
                    $converted['execution']
                );
            } elseif (is_numeric($value)) {
                // handle int, float, numeric strings
                $initializationPhpCode .= sprintf(
                    '\'%s\' => %s,' . chr(10),
                    $key,
                    $value
                );
            } else {
                // handle strings
                $initializationPhpCode .= sprintf(
                    '\'%s\' => \'%s\',' . chr(10),
                    $key,
                    str_replace(['\\', '\''], ['\\\\', '\\\''], $value)
                );
            }
        }
        $initializationPhpCode .= '];' . chr(10);

        return [
            'initialization' => $accumulatedInitializationPhpCode . chr(10) . $initializationPhpCode,
            'execution' => $arrayVariableName
        ];
    }
}
