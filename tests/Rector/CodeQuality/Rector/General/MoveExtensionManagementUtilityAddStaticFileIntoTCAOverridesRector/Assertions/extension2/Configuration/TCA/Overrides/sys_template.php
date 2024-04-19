<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

# sys_template.php file exists
ExtensionManagementUtility::addStaticFile('extension2', 'Configuration/TypoScript', 'Title');
