<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveParameterInAuthenticationServiceRector\Fixture;

use TYPO3\CMS\Core\Authentication\AuthenticationService;

final class MyClass
{
    public function callProcessLoginData(AuthenticationService $serviceObject, array $processedLoginData): void
    {
        $serviceResult = $serviceObject->processLoginData($processedLoginData, 'normal');
        $serviceResult2 = $serviceObject->processLoginData($processedLoginData, 'anything else');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveParameterInAuthenticationServiceRector\Fixture;

use TYPO3\CMS\Core\Authentication\AuthenticationService;

final class MyClass
{
    public function callProcessLoginData(AuthenticationService $serviceObject, array $processedLoginData): void
    {
        $serviceResult = $serviceObject->processLoginData($processedLoginData);
        $serviceResult2 = $serviceObject->processLoginData($processedLoginData);
    }
}

?>
