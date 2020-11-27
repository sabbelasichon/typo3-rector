<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v4\MoveLanguageFilesFromRemovedCmsExtension\Fixture;

$GLOBALS['LANG']->sL('LLL:EXT:cms/web_info/locallang.xlf:pages_1');

$langPrefix = 'LLL:EXT:cms/web_info/locallang.xlf';

$GLOBALS['LANG']->sL($langPrefix . ':pages_1');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v4\MoveLanguageFilesFromRemovedCmsExtension\Fixture;

$GLOBALS['LANG']->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_webinfo.xlf:pages_1');

$langPrefix = 'LLL:EXT:frontend/Resources/Private/Language/locallang_webinfo.xlf';

$GLOBALS['LANG']->sL($langPrefix . ':pages_1');

?>
