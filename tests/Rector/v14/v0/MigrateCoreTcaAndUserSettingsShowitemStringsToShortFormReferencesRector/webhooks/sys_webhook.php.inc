<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRector\Fixture;

return [
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;;config,
                --div--;LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.http_settings,
                --palette--;;http_settings,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;access',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRector\Fixture;

return [
    'types' => [
        '1' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                --palette--;;config,
                --div--;webhooks.db:palette.http_settings,
                --palette--;;http_settings,
                --div--;core.form.tabs:access,
                --palette--;;access',
        ],
    ],
];

?>
