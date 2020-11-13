<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\PropertyUserTsToMethodGetTsConfigOfBackendUserAuthentication\Fixture;

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

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\PropertyUserTsToMethodGetTsConfigOfBackendUserAuthentication\Fixture;

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
