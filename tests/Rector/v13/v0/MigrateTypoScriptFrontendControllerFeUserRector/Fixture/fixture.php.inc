<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

if (is_array($GLOBALS['TSFE']->fe_user->user)
    && $GLOBALS['TSFE']->fe_user->user['uid'] > 0
) {
    $id = $GLOBALS['TSFE']->fe_user->user['uid'];
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

if (is_array($GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->user)
    && $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->user['uid'] > 0
) {
    $id = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->user['uid'];
}

?>
