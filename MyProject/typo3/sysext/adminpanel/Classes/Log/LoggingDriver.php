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

namespace TYPO3\CMS\Adminpanel\Log;

use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;

/**
 * Part of the Doctrine SQL Logging Driver Adapter
 *
 * @internal
 */
final class LoggingDriver extends AbstractDriverMiddleware
{
    private DoctrineSqlLogger $logger;

    public function __construct(DriverInterface $driver, DoctrineSqlLogger $logger)
    {
        parent::__construct($driver);

        $this->logger = $logger;
    }

    public function connect(array $params)
    {
        return new LoggingConnection(parent::connect($params), $this->logger);
    }
}
