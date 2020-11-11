<?php

use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
$documentTemplate->wrapClickMenuOnIcon('Foo', 'Bar');

?>
-----
<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
BackendUtility::wrapClickMenuOnIcon('Foo', 'Bar');
