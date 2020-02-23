<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

$repository = GeneralUtility::makeInstance(PageRepository::class);
$repository->init(true);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

$repository = GeneralUtility::makeInstance(PageRepository::class);

?>
