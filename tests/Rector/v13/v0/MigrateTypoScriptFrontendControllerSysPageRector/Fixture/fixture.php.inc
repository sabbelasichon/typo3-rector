<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerSysPageRector\Fixture;

$sys_page = $GLOBALS['TSFE']->sys_page;
$page = $GLOBALS['TSFE']->sys_page->getPage(2);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerSysPageRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;

$sys_page = GeneralUtility::makeInstance(PageRepository::class);
$page = GeneralUtility::makeInstance(PageRepository::class)->getPage(2);

?>
