<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplacePreviewUrlMethodRector\Fixture;

use TYPO3\CMS\Backend\Utility\BackendUtility;

$pageUid = 1;
$backPath = '';
$rootLine = null;
$anchorSection = '';
$alternativeUrl = '';
$additionalGetVars = '';
$switchFocus = true;

$foo = BackendUtility::getPreviewUrl(
    $pageUid,
    $backPath,
    $rootLine,
    $anchorSection,
    $alternativeUrl,
    $additionalGetVars,
    $switchFocus
);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplacePreviewUrlMethodRector\Fixture;

use TYPO3\CMS\Backend\Routing\PreviewUriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;

$pageUid = 1;
$backPath = '';
$rootLine = null;
$anchorSection = '';
$alternativeUrl = '';
$additionalGetVars = '';
$switchFocus = true;

$foo = (string) PreviewUriBuilder::create($pageUid)->withRootLine($rootLine)->withSection($anchorSection)->withAdditionalQueryParameters($additionalGetVars)->buildUri([PreviewUriBuilder::OPTION_SWITCH_FOCUS => $switchFocus]);

?>
