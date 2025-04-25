<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\MigrateAbstractUserAuthenticationGetSessionIdRector\Fixture;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

$userAuthentication = new BackendUserAuthentication();
$id = $userAuthentication->getSessionId();
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\MigrateAbstractUserAuthenticationGetSessionIdRector\Fixture;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

$userAuthentication = new BackendUserAuthentication();
$id = $userAuthentication->getSession()->getIdentifier();
?>
