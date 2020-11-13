<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

$pageRepository = GeneralUtility::makeInstance(PageRepository::class);
$theFirstPage = $pageRepository->getFirstWebPage(0);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

$pageRepository = GeneralUtility::makeInstance(PageRepository::class);
$rootLevelPages = $pageRepository->getMenu(0, 'uid', 'sorting', '', false);
if (!empty($rootLevelPages)) {
    $theFirstPage = reset($rootLevelPages);
}

?>
