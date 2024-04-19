<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile('extension3', 'Configuration/TypoScript', 'Title');

ExtensionManagementUtility::addStaticFile('extension3', 'Configuration/OtherFolder', 'Title');
