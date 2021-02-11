<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$domain1 = BackendUtility::getViewDomain(1);
$rootline = [1, 2, 3];
$domain2 = BackendUtility::getViewDomain(1, $rootline);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
$site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId(1);

$domain1 = $site->getRouter()->generateUri(1);
$rootline = [1, 2, 3];
$site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId(1, $rootline);
$domain2 = $site->getRouter()->generateUri(1);

?>
