<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\Fixture;

use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Extbase\Security\Exception\InvalidArgumentForHashGenerationException;
use TYPO3\CMS\Extbase\Security\Exception\InvalidHashException;

class MyController
{
    private HashService $hashService;

    public function __construct(HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    public function myAction()
    {
        try {
            $this->validate();
        } catch (InvalidArgumentForHashGenerationException $exception) {

        }
    }

    /**
     * @throws InvalidArgumentForHashGenerationException
     * @throws InvalidHashException
     */
    private function validate()
    {
        $this->hashService->generateHmac('foo');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\Fixture;

use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Exception\Crypto\InvalidHashStringException;
class MyController
{
    private HashService $hashService;

    public function __construct(HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    public function myAction()
    {
        try {
            $this->validate();
        } catch (InvalidHashStringException $exception) {

        }
    }

    /**
     * @throws InvalidHashStringException
     * @throws InvalidHashStringException
     */
    private function validate()
    {
        $this->hashService->hmac('foo', 'changeMe');
    }
}

?>
