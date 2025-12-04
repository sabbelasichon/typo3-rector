<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

return [
    'palettes' => [
        'authentication' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.palettes.authentication',
            'showitem' => 'mfa_providers',
        ],
        'permissionGeneral' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.palettes.permissionGeneral',
            'showitem' => '
                tables_modify,
                --linebreak--, non_exclude_fields
            ',
        ],
        'permissionLanguages' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.palettes.permissionLanguages',
            'showitem' => 'allowed_languages',
        ],
        'permissionSpecific' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.palettes.permissionSpecific',
            'showitem' => '
                pagetypes_select,
                --linebreak--, explicit_allowdeny
            ',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

return [
    'palettes' => [
        'authentication' => [
            'label' => 'core.form.palettes:authentication',
            'showitem' => 'mfa_providers',
        ],
        'permissionGeneral' => [
            'label' => 'core.form.palettes:permission_general',
            'showitem' => '
                tables_modify,
                --linebreak--, non_exclude_fields
            ',
        ],
        'permissionLanguages' => [
            'label' => 'core.form.palettes:permission_languages',
            'showitem' => 'allowed_languages',
        ],
        'permissionSpecific' => [
            'label' => 'core.form.palettes:permission_specific',
            'showitem' => '
                pagetypes_select,
                --linebreak--, explicit_allowdeny
            ',
        ],
    ],
];

?>
