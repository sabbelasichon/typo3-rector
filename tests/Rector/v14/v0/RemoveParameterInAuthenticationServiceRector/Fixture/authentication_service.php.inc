<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveParameterInAuthenticationServiceRector\Fixture;

use TYPO3\CMS\Core\Authentication\AuthenticationService;

final class MyAuthenticationService extends AuthenticationService
{
    public function processLoginData(array &$loginData)
    {
        return true;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveParameterInAuthenticationServiceRector\Fixture;

use TYPO3\CMS\Core\Authentication\AuthenticationService;

final class MyAuthenticationService extends AuthenticationService
{
    public function processLoginData(array &$loginData): bool|int
    {
        return true;
    }
}

?>
