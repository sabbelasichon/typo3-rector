<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Compiler;

use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

/**
 * Class ViewHelperCompiler
 *
 * Assistant class designed to exclusively compile ViewHelpers
 * of common types. Each ViewHelper that wants to compile itself
 * efficiently using a common pattern can utilise this class
 * in an overridden `compile()` method to generate PHP code.
 *
 * Every method on this class returns an array containing exactly
 * two entries, as required to compile a ViewHelper:
 *
 * - initialization code as string (index 0)
 * - execution code as string (index 1)
 *
 * Methods on this class can be called as:
 *
 *     list ($initialization, $execution) = ViewHelperCompiler::getInstance()
 *         ->compileWithStaticMethod($this, $argumentsName, $closureName);
 *     // where you select the appropriate compiling method to use according
 *     // to your ViewHelper's business logic
 *
 * And the output variables must respectively then be appended
 * to the initialization code passed to the `compile()` function:
 *
 *     $initializationPhpCode .= $initialization;
 *
 * And returned from the `compile()` method as execution code:
 *
 *     return $execution;
 *
 * Note that not all ViewHelpers will generate initialisation code;
 * it is recommended that you append the `$initialization` code
 * string variable anyway since special implementations or future
 * changes in Fluid may cause initialisation code to be generated.
 *
 * @deprecated Unused. Will be removed. Inline access to the constants and compileWithCallToStaticMethod() instead.
 */
class ViewHelperCompiler
{
    public const RENDER_STATIC = 'renderStatic';
    public const DEFAULT_INIT = '';

    /**
     * Factory method to create an instance; since this class is
     * exclusively used as a "fire once" command where it is ideal
     * to chain the instance creation and method to be called into
     * a single line.
     *
     * @return static
     */
    public static function getInstance()
    {
        return new static();
    }

    /**
     * The simples of compilation methods designed to work well
     * for ViewHelpers that implement `renderStatic` or a similar
     * statically callable public method that makes sense to call
     * each time the ViewHelper needs to render.
     *
     * It is also possible to compile so the resulting call is
     * made to a non-ViewHelper class which may be even more efficient
     * when the ViewHelper is a wrapper for a framework method.
     *
     * See class doc comment about consuming the returned values!
     *
     * @param ViewHelperInterface $viewHelper ViewHelper instance to be compiled
     * @param string $argumentsName Name of arguments variable passed to `compile()` method
     * @param string $renderChildrenClosureName Name of renderChildren closure passed to `compile()` method
     * @param string $method The name of the class' method to be called
     * @param string|null $onClass Class name which contains the method; null means use ViewHelper's class name.
     * @return array
     */
    public function compileWithCallToStaticMethod(ViewHelperInterface $viewHelper, $argumentsName, $renderChildrenClosureName, $method = self::RENDER_STATIC, $onClass = null)
    {
        $onClass = $onClass ?: get_class($viewHelper);
        return [
            self::DEFAULT_INIT,
            sprintf(
                '%s::%s(%s, %s, $renderingContext)',
                $onClass,
                $method,
                $argumentsName,
                $renderChildrenClosureName
            )
        ];
    }
}
