<?php

\TYPO3\CMS\Backend\Utility\BackendUtility::shortcutExists('http://example.com');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Backend\Shortcut\ShortcutRepository::class)->shortcutExists('http://example.com');
