<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

$hashService = GeneralUtility::makeInstance(HashService::class);

$hashService->validateHmac('123');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\Fixture;

use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hashService = GeneralUtility::makeInstance(HashService::class);

$hashService->validateHmac('123');

?>
