<?php

declare(strict_types=1);

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

namespace TYPO3\CMS\Backend\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Sets up click-jacking prevention for HTTP requests by adding HTTP headers for the response
 *
 * @internal
 */
class AdditionalResponseHeaders implements MiddlewareInterface
{
    /**
     * Adds HTTP headers defined in $GLOBALS['TYPO3_CONF_VARS']['BE']['HTTP']['Response']['Headers']
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // Remove HSTS header, if [BE][lockSSL] is not configured to use SSL
        if ((bool)$GLOBALS['TYPO3_CONF_VARS']['BE']['lockSSL'] === false) {
            unset($GLOBALS['TYPO3_CONF_VARS']['BE']['HTTP']['Response']['Headers']['strictTransportSecurity']);
        }

        foreach ($GLOBALS['TYPO3_CONF_VARS']['BE']['HTTP']['Response']['Headers'] ?? [] as $header) {
            [$headerName, $value] = explode(':', $header, 2);
            $response = $response->withAddedHeader($headerName, trim($value));
        }
        return $response;
    }
}
