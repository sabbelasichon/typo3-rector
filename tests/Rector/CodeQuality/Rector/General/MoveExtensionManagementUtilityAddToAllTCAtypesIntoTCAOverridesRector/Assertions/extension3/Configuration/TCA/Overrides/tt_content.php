<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'new_field', '', 'after:a');

ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'new_field', '', 'after:b');
