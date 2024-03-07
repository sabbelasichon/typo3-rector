<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ChangeSignatureOfConnectionQuoteRector\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('table');
$input = 1;
$queryBuilder->quote($input, 0);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ChangeSignatureOfConnectionQuoteRector\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('table');
$input = 1;
$queryBuilder->quote((string) $input);
?>
