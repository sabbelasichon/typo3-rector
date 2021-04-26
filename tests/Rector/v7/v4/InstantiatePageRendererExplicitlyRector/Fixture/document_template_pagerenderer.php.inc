<?php

use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
$pageRenderer = $documentTemplate->getPageRenderer();

?>
-----
<?php

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

?>
