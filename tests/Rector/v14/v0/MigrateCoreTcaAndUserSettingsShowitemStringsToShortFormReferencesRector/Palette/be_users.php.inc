<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

return [
    'palettes' => [
        'account' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.palettes.account',
            'showitem' => '
                admin,
                --linebreak--, username, password
            ',
        ],
        'authentication' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.palettes.authentication',
            'showitem' => 'mfa',
        ],
        'permissionLanguages' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.palettes.permissionLanguages',
            'showitem' => 'allowed_languages',
        ],
        'status' => [
            'showitem' => 'disable, lastlogin',
        ],
        'timeRestriction' => [
            'showitem' => 'starttime, endtime',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

return [
    'palettes' => [
        'account' => [
            'label' => 'core.form.palettes:account',
            'showitem' => '
                admin,
                --linebreak--, username, password
            ',
        ],
        'authentication' => [
            'label' => 'core.form.palettes:authentication',
            'showitem' => 'mfa',
        ],
        'permissionLanguages' => [
            'label' => 'core.form.palettes:permission_languages',
            'showitem' => 'allowed_languages',
        ],
        'status' => [
            'showitem' => 'disable, lastlogin',
        ],
        'timeRestriction' => [
            'showitem' => 'starttime, endtime',
        ],
    ],
];

?>
