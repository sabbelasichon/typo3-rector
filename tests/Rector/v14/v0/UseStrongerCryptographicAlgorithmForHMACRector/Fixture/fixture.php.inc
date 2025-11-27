<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrongerCryptographicAlgorithmForHMACRector\Fixture;

use TYPO3\CMS\Core\Crypto\HashService;

final class SomeClass
{
    public function action(HashService $hashService, string $data): void
    {
        $hmac = $hashService->hmac($data, 'my-secret');
        $hmac2 = $hashService->hmac($data, 'my-secret', 'sha1');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrongerCryptographicAlgorithmForHMACRector\Fixture;

use TYPO3\CMS\Core\Crypto\HashAlgo;
use TYPO3\CMS\Core\Crypto\HashService;

final class SomeClass
{
    public function action(HashService $hashService, string $data): void
    {
        $hmac = $hashService->hmac($data, 'my-secret', HashAlgo::SHA3_256);
        $hmac2 = $hashService->hmac($data, 'my-secret', 'sha1');
    }
}

?>
