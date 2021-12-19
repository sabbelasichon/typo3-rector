<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\RemoveBackendUtilityViewOnClickUsageRector\Fixture;

use TYPO3\CMS\Backend\Utility\BackendUtility;

$pageId = 0;
$backPath = '';
$rootLine = null;
$section = '';
$viewUri = '';
$getVars = '';
$switchFocus = true;

$onclick = BackendUtility::viewOnClick(
    $pageId, $backPath, $rootLine, $section,
    $viewUri, $getVars, $switchFocus
);

$testOnlyMandatoryParams = BackendUtility::viewOnClick($pageId);

$foo = BackendUtility::viewOnClick($pageId, $backPath, $rootLine);

$bar = BackendUtility::viewOnClick($pageId, $backPath, []);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\RemoveBackendUtilityViewOnClickUsageRector\Fixture;

use TYPO3\CMS\Backend\Routing\PreviewUriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;

$pageId = 0;
$backPath = '';
$rootLine = null;
$section = '';
$viewUri = '';
$getVars = '';
$switchFocus = true;

$onclick = PreviewUriBuilder::create($pageId, $viewUri)->withRootLine($rootLine)->withSection($section)->withAdditionalQueryParameters($getVars)->buildDispatcherDataAttributes([PreviewUriBuilder::OPTION_SWITCH_FOCUS => $switchFocus]);

$testOnlyMandatoryParams = PreviewUriBuilder::create($pageId)->buildDispatcherDataAttributes();

$foo = PreviewUriBuilder::create($pageId)->withRootLine($rootLine)->buildDispatcherDataAttributes();

$bar = PreviewUriBuilder::create($pageId)->withRootLine([])->buildDispatcherDataAttributes();

?>
