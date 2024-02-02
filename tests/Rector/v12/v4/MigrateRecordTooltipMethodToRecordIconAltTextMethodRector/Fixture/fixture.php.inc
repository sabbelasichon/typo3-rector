<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRecordTooltipMethodToRecordIconAltTextMethodRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRecordTooltipMethodToRecordIconAltTextMethodRector\Source\CustomBackendUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

$link = '<a href="..." ' . BackendUtility::getRecordToolTip('tooltip') . '>my link</a>';

echo CustomBackendUtility::getRecordToolTip();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRecordTooltipMethodToRecordIconAltTextMethodRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRecordTooltipMethodToRecordIconAltTextMethodRector\Source\CustomBackendUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

$link = '<a href="..." ' . ('title="' . BackendUtility::getRecordIconAltText('tooltip') . '"') . '>my link</a>';

echo CustomBackendUtility::getRecordToolTip();

?>
