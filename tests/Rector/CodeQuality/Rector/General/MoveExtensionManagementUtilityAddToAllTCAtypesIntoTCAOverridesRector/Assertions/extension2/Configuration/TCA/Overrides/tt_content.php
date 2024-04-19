<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

# tt_content.php file exists
ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'new_field', '', 'after:b');
