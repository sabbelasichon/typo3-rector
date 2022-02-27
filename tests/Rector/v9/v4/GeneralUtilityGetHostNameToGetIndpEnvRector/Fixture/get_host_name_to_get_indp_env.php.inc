<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\GeneralUtilityGetHostNameToGetIndpEnvRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::getHostName();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\GeneralUtilityGetHostNameToGetIndpEnvRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::getIndpEnv('HTTP_HOST');

?>
