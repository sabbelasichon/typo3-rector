<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\Fixture;

use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

$hashService = new HashService();

$generatedHash = $hashService->generateHmac('123');
$isValidHash = $hashService->validateHmac('123', $generatedHash);

$stringWithAppendedHash = $hashService->appendHmac('123');
$validatedStringWithHashRemoved = $hashService->validateAndStripHmac($stringWithAppendedHash);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\Fixture;

use TYPO3\CMS\Core\Crypto\HashService;

$hashService = new HashService();

$generatedHash = $hashService->hmac('123', 'myAdditionalSecret');
$isValidHash = $hashService->validateHmac('123', 'myAdditionalSecret', $generatedHash);

$stringWithAppendedHash = $hashService->appendHmac('123', 'myAdditionalSecret');
$validatedStringWithHashRemoved = $hashService->validateAndStripHmac($stringWithAppendedHash, 'myAdditionalSecret');

?>
