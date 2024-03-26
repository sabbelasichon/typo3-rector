<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\ErrorHandler;

interface ErrorHandlerInterface
{
    /**
     * Handle errors caused by parsing templates, for example when
     * invalid arguments are used.
     *
     * @return string
     */
    public function handleParserError(\TYPO3Fluid\Fluid\Core\Parser\Exception $error);

    /**
     * Handle errors caused by invalid expressions, e.g. errors
     * raised from misuse of `{variable xyz 123}` style expressions,
     * such as the casting expression `{variable as type}`.
     *
     * @return string
     */
    public function handleExpressionError(\TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\ExpressionException $error);

    /**
     * Can be implemented to handle a ViewHelper errors which are
     * normally thrown from inside ViewHelpers during rendering.
     *
     * @return string
     */
    public function handleViewHelperError(\TYPO3Fluid\Fluid\Core\ViewHelper\Exception $error);

    /**
     * Can be implemented to handle "cannot compile" errors in
     * desired ways (normally this simply disables the compiling,
     * but if your application deems compiler errors fatal then
     * you can throw a different exception type here).
     *
     * @return string
     */
    public function handleCompilerError(\TYPO3Fluid\Fluid\Core\Compiler\StopCompilingException $error);

    /**
     * @return string
     */
    public function handleViewError(\TYPO3Fluid\Fluid\View\Exception $error);
}
