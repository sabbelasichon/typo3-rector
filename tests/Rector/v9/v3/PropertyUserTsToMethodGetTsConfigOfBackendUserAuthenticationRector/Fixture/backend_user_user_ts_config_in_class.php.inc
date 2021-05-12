<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector\Fixture;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

class MyClass
{
    /**
     * @var BackendUserAuthentication
     */
    private $backendUserAuthentication;

    public function __construct()
    {
        $this->backendUserAuthentication = $GLOBALS['BE_USER'];
    }

    public function getUserTsConfig(): array
    {
        return $this->backendUserAuthentication->userTS;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector\Fixture;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

class MyClass
{
    /**
     * @var BackendUserAuthentication
     */
    private $backendUserAuthentication;

    public function __construct()
    {
        $this->backendUserAuthentication = $GLOBALS['BE_USER'];
    }

    public function getUserTsConfig(): array
    {
        return $this->backendUserAuthentication->getTSConfig();
    }
}

?>
