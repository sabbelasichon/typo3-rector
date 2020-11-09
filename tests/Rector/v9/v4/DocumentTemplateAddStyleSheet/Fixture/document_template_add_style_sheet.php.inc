<?php

use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
$documentTemplate->addStyleSheet('foo', 'foo.css');
$documentTemplate->addStyleSheet('bar', 'bar.css', 'Title');
$documentTemplate->addStyleSheet('baz', 'baz.css', 'Title', 'alternate');

?>
-----
<?php

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
GeneralUtility::makeInstance(PageRenderer::class)->addCssFile('foo.css', 'stylesheet', 'screen', '');
GeneralUtility::makeInstance(PageRenderer::class)->addCssFile('bar.css', 'stylesheet', 'screen', 'Title');
GeneralUtility::makeInstance(PageRenderer::class)->addCssFile('baz.css', 'alternate', 'screen', 'Title');
