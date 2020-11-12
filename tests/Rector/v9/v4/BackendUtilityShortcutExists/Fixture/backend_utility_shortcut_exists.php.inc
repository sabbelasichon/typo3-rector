<?php

\TYPO3\CMS\Backend\Utility\BackendUtility::shortcutExists('http://example.com');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Backend\Shortcut\ShortcutRepository;
GeneralUtility::makeInstance(ShortcutRepository::class)->shortcutExists('http://example.com');
