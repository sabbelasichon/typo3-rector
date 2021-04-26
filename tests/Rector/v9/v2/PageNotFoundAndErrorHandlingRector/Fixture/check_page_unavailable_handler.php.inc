<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v2\PageNotFoundAndErrorHandlingRector\Fixture;

if($GLOBALS['TSFE']->checkPageUnavailableHandler()) {
    // Do something
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v2\PageNotFoundAndErrorHandlingRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
if($GLOBALS['TYPO3_CONF_VARS']['FE']['pageUnavailable_handling'] && !GeneralUtility::cmpIP(GeneralUtility::getIndpEnv('REMOTE_ADDR'), $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'])) {
    // Do something
}

?>
