<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\OptionalConstructorToHardRequirement\Fixture;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClassWithLogManager
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var mixed|BackendUserAuthentication|null
     */
    private $backendUser;

    public function __construct(LogManager $logManager = null, ConnectionPool $connectionPool = null, BackendUserAuthentication $backendUser = null)
    {
        $this->backendUser = $backendUser ?? $GLOBALS['BE_USER'] ?? null;
        $connectionPool = $connectionPool ?? GeneralUtility::makeInstance(ConnectionPool::class);
        $this->connection = $connectionPool->getConnectionForTable('sys_language');
        $logManager = $logManager ?? GeneralUtility::makeInstance(LogManager::class);
        $this->logger = $logManager->getLogger(__CLASS__);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\OptionalConstructorToHardRequirement\Fixture;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClassWithLogManager
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var mixed|BackendUserAuthentication|null
     */
    private $backendUser;

    public function __construct(LogManager $logManager, ConnectionPool $connectionPool, BackendUserAuthentication $backendUser = null)
    {
        $this->backendUser = $backendUser ?? $GLOBALS['BE_USER'] ?? null;
        $this->connection = $connectionPool->getConnectionForTable('sys_language');
        $this->logger = $logManager->getLogger(__CLASS__);
    }
}

?>
