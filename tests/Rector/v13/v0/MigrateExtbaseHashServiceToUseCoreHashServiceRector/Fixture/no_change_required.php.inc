<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\Fixture;

use TYPO3\CMS\Core\Crypto\HashService;

$hashService = new HashService();

$additionalSecret = 'secret';

$generatedHash = $hashService->hmac('123', $additionalSecret);
$isValidHash = $hashService->validateHmac('123', $additionalSecret, $generatedHash);

$stringWithAppendedHash = $hashService->appendHmac('123', $additionalSecret);
$validatedStringWithHashRemoved = $hashService->validateAndStripHmac($stringWithAppendedHash, $additionalSecret);

?>
