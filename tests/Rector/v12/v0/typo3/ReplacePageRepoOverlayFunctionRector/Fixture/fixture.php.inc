<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplacePageRepoOverlayFunctionRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/** @var PageRepository $pageRepo */
$pageRepo = GeneralUtility::makeInstance(PageRepository::class);
$pageRepo->getRecordOverlay('', [], '');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplacePageRepoOverlayFunctionRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/** @var PageRepository $pageRepo */
$pageRepo = GeneralUtility::makeInstance(PageRepository::class);
$pageRepo->getLanguageOverlay('', []);

?>
