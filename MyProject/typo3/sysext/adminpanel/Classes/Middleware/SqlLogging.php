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

namespace TYPO3\CMS\Adminpanel\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Adminpanel\Log\DoctrineSqlLoggingMiddleware;
use TYPO3\CMS\Adminpanel\Utility\StateUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * Enable sql logging for the admin panel
 *
 * @internal
 */
class SqlLogging implements MiddlewareInterface
{
    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {}

    /**
     * Enable SQL Logging as early as possible to catch all queries if the admin panel is active
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (StateUtility::isActivatedForUser() && StateUtility::isOpen()) {
            $connection = $this->connectionPool->getConnectionByName(ConnectionPool::DEFAULT_CONNECTION_NAME);
            foreach ($connection->getConfiguration()->getMiddlewares() as $middleware) {
                if ($middleware instanceof DoctrineSqlLoggingMiddleware) {
                    $middleware->enable();
                    break;
                }
            }
        }
        return $handler->handle($request);
    }
}
