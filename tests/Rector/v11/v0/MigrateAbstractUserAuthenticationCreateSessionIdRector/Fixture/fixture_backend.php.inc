<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\MigrateAbstractUserAuthenticationCreateSessionIdRector\Fixture;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

$userAuthentication = new BackendUserAuthentication();
$sessionId = $userAuthentication->createSessionId();
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\MigrateAbstractUserAuthenticationCreateSessionIdRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

$userAuthentication = new BackendUserAuthentication();
$sessionId = GeneralUtility::makeInstance(Random::class)->generateRandomHexString(32);
?>
