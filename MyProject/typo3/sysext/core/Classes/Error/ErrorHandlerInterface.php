<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Core\Error;

/**
 * Error handler interface for TYPO3
 *
 * This file is a backport from TYPO3 Flow
 */
interface ErrorHandlerInterface
{
    // Constants to make the implications of the PHP error handling API a bit more obvious.
    public const ERROR_HANDLED = true;
    public const PROPAGATE_ERROR = false;

    /**
     * Registers this class as default error handler
     *
     * If dependencies need to be added using injector methods, the error handler may
     * also be registered later on, within the optional registerErrorHandler() method.
     *
     * @param int $errorHandlerErrors The integer representing the E_* error level which should be
     */
    public function __construct($errorHandlerErrors);

    /**
     * Defines which error levels should result in an exception thrown.
     *
     * @param int $exceptionalErrors The integer representing the E_* error level to handle as exceptions
     */
    public function setExceptionalErrors($exceptionalErrors);

    /**
     * Handles an error.
     * If the error is registered as exceptionalError it will by converted into an exception, to be handled
     * by the configured exceptionhandler. Additionally the error message is written to the configured logs.
     * If application is backend, the error message is also added to the flashMessageQueue, in frontend the
     * error message is displayed in the admin panel (as TsLog message).
     *
     * @param int $errorLevel The error level - one of the E_* constants
     * @param string $errorMessage The error message
     * @param string $errorFile Name of the file the error occurred in
     * @param int $errorLine Line number where the error occurred
     * @return bool
     * @throws \TYPO3\CMS\Core\Error\Exception with the data passed to this method if the error is registered as exceptionalError
     */
    public function handleError($errorLevel, $errorMessage, $errorFile, $errorLine);
}
