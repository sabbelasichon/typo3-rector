<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\ErrorHandler;

/**
 * Class TolerantErrorHandler
 *
 * Turns most frequently encountered types of exceptions into
 * friendlier output; swallows exceptions and returns a simple
 * string describing the problem.
 *
 * Useful in production - allows template to be rendered even
 * if part of the template or cascaded rendering causes errors.
 */
class TolerantErrorHandler implements ErrorHandlerInterface
{
    /**
     * @param \TYPO3Fluid\Fluid\Core\Parser\Exception $error
     * @return string
     */
    public function handleParserError(\TYPO3Fluid\Fluid\Core\Parser\Exception $error)
    {
        return 'Parser error: ' . $error->getMessage() . ' Offending code: ';
    }

    /**
     * @param \TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\ExpressionException $error
     * @return string
     */
    public function handleExpressionError(\TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\ExpressionException $error)
    {
        return 'Invalid expression: ' . $error->getMessage();
    }

    /**
     * @param \TYPO3Fluid\Fluid\Core\ViewHelper\Exception $error
     * @return string
     */
    public function handleViewHelperError(\TYPO3Fluid\Fluid\Core\ViewHelper\Exception $error)
    {
        return 'ViewHelper error: ' . $error->getMessage() . ' - Offending code: ';
    }

    /**
     * @param \TYPO3Fluid\Fluid\Core\Compiler\StopCompilingException $error
     * @return string
     */
    public function handleCompilerError(\TYPO3Fluid\Fluid\Core\Compiler\StopCompilingException $error)
    {
        return '';
    }

    /**
     * @param \TYPO3Fluid\Fluid\View\Exception $error
     * @return string
     */
    public function handleViewError(\TYPO3Fluid\Fluid\View\Exception $error)
    {
        if ($error instanceof \TYPO3Fluid\Fluid\View\Exception\InvalidSectionException) {
            return 'Section rendering error: ' . $error->getMessage() . ' Section rendering is mandatory; "optional" is false.';
        }
        return 'View error: ' . $error->getMessage();
    }
}
