<?php

namespace TYPO3Fluid\Fluid\Core\ErrorHandler;

if (interface_exists('TYPO3Fluid\Fluid\Core\ErrorHandler\ErrorHandlerInterface')) {
    return;
}

interface ErrorHandlerInterface
{
    /**
     * Handle errors caused by parsing templates, for example when
     * invalid arguments are used.
     */
    public function handleParserError(\TYPO3Fluid\Fluid\Core\Parser\Exception $error): string;

    /**
     * Handle errors caused by invalid expressions, e.g. errors
     * raised from misuse of `{variable xyz 123}` style expressions,
     * such as the casting expression `{variable as type}`.
     */
    public function handleExpressionError(\TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\ExpressionException $error): string;

    /**
     * Can be implemented to handle a ViewHelper errors which are
     * normally thrown from inside ViewHelpers during rendering.
     */
    public function handleViewHelperError(\TYPO3Fluid\Fluid\Core\ViewHelper\Exception $error): string;

    /**
     * Can be implemented to handle "cannot compile" errors in
     * desired ways (normally this simply disables the compiling,
     * but if your application deems compiler errors fatal then
     * you can throw a different exception type here).
     */
    public function handleCompilerError(\TYPO3Fluid\Fluid\Core\Compiler\StopCompilingException $error): string;

    public function handleViewError(\TYPO3Fluid\Fluid\View\Exception $error): string;
}
