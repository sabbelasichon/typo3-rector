<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceTSFECheckEnableFieldsRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$row = [];

$foo = $GLOBALS['TSFE']->checkEnableFields($row);
$foofoo = $GLOBALS['TSFE']->checkPagerecordForIncludeSection($row);

/** @var TypoScriptFrontendController $typoscriptFrontendController */
$typoscriptFrontendController = $GLOBALS['TSFE'];
$bar = $typoscriptFrontendController->checkEnableFields($row);
$baz = $typoscriptFrontendController->checkPagerecordForIncludeSection($row);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceTSFECheckEnableFieldsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Domain\Access\RecordAccessVoter\RecordAccessVoter;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$row = [];

$foo = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGranted('pages', $row, $GLOBALS['TSFE']->getContext());
$foofoo = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGrantedForPageInRootLine($row, $GLOBALS['TSFE']->getContext());

/** @var TypoScriptFrontendController $typoscriptFrontendController */
$typoscriptFrontendController = $GLOBALS['TSFE'];
$bar = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGranted('pages', $row, $typoscriptFrontendController->getContext());
$baz = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGrantedForPageInRootLine($row, $typoscriptFrontendController->getContext());

?>
