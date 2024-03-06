<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ChangeSignatureForLastInsertIdRector\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$connection = GeneralUtility::makeInstance(ConnectionPool::class)
    ->getConnectionForTable('tx_myextension_mytable');

$uid = $connection->lastInsertId('tx_myextension_mytable');
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ChangeSignatureForLastInsertIdRector\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$connection = GeneralUtility::makeInstance(ConnectionPool::class)
    ->getConnectionForTable('tx_myextension_mytable');

$uid = $connection->lastInsertId();
?>
