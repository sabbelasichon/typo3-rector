<?php

$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
