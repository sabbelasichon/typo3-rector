<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceTSFEWithContextMethodsRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/** @var TypoScriptFrontendController $tsfe */
$tsfe = $GLOBALS['TSFE'];

$GLOBALS['TSFE']->initUserGroups();
$one = $GLOBALS['TSFE']->isUserOrGroupSet();
$two = $GLOBALS['TSFE']->isBackendUserLoggedIn();
$three = $GLOBALS['TSFE']->doWorkspacePreview();
$four = $GLOBALS['TSFE']->whichWorkspace();

$tsfe->initUserGroups();
$foo = $tsfe->isUserOrGroupSet();
$bar = $tsfe->isBackendUserLoggedIn();
$baz = $tsfe->doWorkspacePreview();
$foofoo = $tsfe->whichWorkspace();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceTSFEWithContextMethodsRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/** @var TypoScriptFrontendController $tsfe */
$tsfe = $GLOBALS['TSFE'];

$GLOBALS['TSFE']->getContext()->setAspect('frontend.user', $GLOBALS['TSFE']->fe_user->createUserAspect());
$one = $GLOBALS['TSFE']->getContext()->getAspect('frontend.user')->isUserOrGroupSet();
$two = $GLOBALS['TSFE']->getContext()->getPropertyFromAspect('backend.user', 'isLoggedIn', false);
$three = $GLOBALS['TSFE']->getContext()->getPropertyFromAspect('workspace', 'isOffline', false);
$four = $GLOBALS['TSFE']->getContext()->getPropertyFromAspect('workspace', 'id', 0);

$tsfe->getContext()->setAspect('frontend.user', $tsfe->fe_user->createUserAspect());
$foo = $tsfe->getContext()->getAspect('frontend.user')->isUserOrGroupSet();
$bar = $tsfe->getContext()->getPropertyFromAspect('backend.user', 'isLoggedIn', false);
$baz = $tsfe->getContext()->getPropertyFromAspect('workspace', 'isOffline', false);
$foofoo = $tsfe->getContext()->getPropertyFromAspect('workspace', 'id', 0);

?>
