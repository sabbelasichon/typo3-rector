<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

$repository = GeneralUtility::makeInstance(PageRepository::class);
$repository->init(true);

GeneralUtility::makeInstance(PageRepository::class)->init();

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

$repository = GeneralUtility::makeInstance(PageRepository::class);

?>
