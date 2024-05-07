<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v1\MigrateGeneralUtilityHmacToHashServiceHmacRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$hmac = GeneralUtility::hmac('some-input', 'some-secret');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v1\MigrateGeneralUtilityHmacToHashServiceHmacRector\Fixture;

use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hmac = GeneralUtility::makeInstance(HashService::class)->hmac('some-input', 'some-secret');

?>
