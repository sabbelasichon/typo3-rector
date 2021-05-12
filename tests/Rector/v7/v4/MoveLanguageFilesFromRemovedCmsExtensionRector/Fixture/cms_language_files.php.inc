<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v4\MoveLanguageFilesFromRemovedCmsExtensionRector\Fixture;

$GLOBALS['LANG']->sL('LLL:EXT:cms/web_info/locallang.xlf:pages_1');

$langPrefix = 'LLL:EXT:cms/web_info/locallang.xlf';

$GLOBALS['LANG']->sL($langPrefix . ':pages_1');

$types = [
    'types' => [
        '1' => ['showitem' => 'sys_language_uid,--palette--;;,l10n_parent,l10n_diffsource,hidden,--palette--;;1,title,slug,id,ean,description,manufacturer,moq,weight,images,categories,feature_groups,variants,filter_values,--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime,endtime'],
    ],
];

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v4\MoveLanguageFilesFromRemovedCmsExtensionRector\Fixture;

$GLOBALS['LANG']->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_webinfo.xlf:pages_1');

$langPrefix = 'LLL:EXT:frontend/Resources/Private/Language/locallang_webinfo.xlf';

$GLOBALS['LANG']->sL($langPrefix . ':pages_1');

$types = [
    'types' => [
        '1' => ['showitem' => 'sys_language_uid,--palette--;;,l10n_parent,l10n_diffsource,hidden,--palette--;;1,title,slug,id,ean,description,manufacturer,moq,weight,images,categories,feature_groups,variants,filter_values,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime,endtime'],
    ],
];

?>
