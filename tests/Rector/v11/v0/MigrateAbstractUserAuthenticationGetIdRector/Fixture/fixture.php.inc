<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\MigrateAbstractUserAuthenticationGetIdRector\Fixture;

use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

$userAuthentication = new FrontendUserAuthentication();
$id = $userAuthentication->id;
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\MigrateAbstractUserAuthenticationGetIdRector\Fixture;

use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

$userAuthentication = new FrontendUserAuthentication();
$id = $userAuthentication->getSession()->getIdentifier();
?>
