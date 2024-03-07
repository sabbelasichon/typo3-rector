<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

$hashService = GeneralUtility::makeInstance(HashService::class);

$generatedHash = $hashService->generateHmac('123');
$isValidHash = $hashService->validateHmac('123', $generatedHash);

$stringWithAppendedHash = $hashService->appendHmac('123');
$validatedStringWithHashRemoved = $hashService->validateAndStripHmac($stringWithAppendedHash);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\Fixture;

use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hashService = GeneralUtility::makeInstance(HashService::class);

$generatedHash = $hashService->hmac('123', 'changeMe');
$isValidHash = $hashService->validateHmac('123', 'changeMe', $generatedHash);

$stringWithAppendedHash = $hashService->appendHmac('123', 'changeMe');
$validatedStringWithHashRemoved = $hashService->validateAndStripHmac($stringWithAppendedHash, 'changeMe');

?>
