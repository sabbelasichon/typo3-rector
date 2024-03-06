<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ChangeSingnatureOfConnectionQuoteRector\Fixture;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$connection = GeneralUtility::makeInstance(Connection::class);
$connection->quote('foo', 0);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ChangeSingnatureOfConnectionQuoteRector\Fixture;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$connection = GeneralUtility::makeInstance(Connection::class);
$connection->quote('foo');
?>
