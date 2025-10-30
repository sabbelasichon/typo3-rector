<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

return [
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;account,
                    usergroup,
                    --palette--;;authentication,
                --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.personal_data,
                    realName, email, avatar, lang,
                --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.permissionRecords,
                    --palette--;;permissionLanguages,
                --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.permissions,
                    userMods, workspace_perms,
                --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.mounts_and_workspaces,
                    db_mountpoints, options, file_mountpoints, file_permissions, category_perms,
                --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.options,
                    TSconfig,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;status,
                    --palette--;;timeRestriction,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    description,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
            'creationOptions' => [
                'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.types.user',
            ],
        ],
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;account,
                    usergroup,
                    --palette--;;authentication,
                --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.personal_data,
                    realName, email, avatar, lang,
                --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.options,
                    TSconfig, db_mountpoints, options, file_mountpoints,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;status,
                    --palette--;;timeRestriction,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    description,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
            'creationOptions' => [
                'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.types.admin',
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

return [
    'types' => [
        '0' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    --palette--;;account,
                    usergroup,
                    --palette--;;authentication,
                --div--;core.form.tabs:personaldata,
                    realName, email, avatar, lang,
                --div--;core.form.tabs:recordpermissions,
                    --palette--;;permissionLanguages,
                --div--;core.form.tabs:modulepermissions,
                    userMods, workspace_perms,
                --div--;core.form.tabs:mounts,
                    db_mountpoints, options, file_mountpoints, file_permissions, category_perms,
                --div--;core.form.tabs:options,
                    TSconfig,
                --div--;core.form.tabs:access,
                    --palette--;;status,
                    --palette--;;timeRestriction,
                --div--;core.form.tabs:notes,
                    description,
                --div--;core.form.tabs:extended,
            ',
            'creationOptions' => [
                'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.types.user',
            ],
        ],
        '1' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    --palette--;;account,
                    usergroup,
                    --palette--;;authentication,
                --div--;core.form.tabs:personaldata,
                    realName, email, avatar, lang,
                --div--;core.form.tabs:options,
                    TSconfig, db_mountpoints, options, file_mountpoints,
                --div--;core.form.tabs:access,
                    --palette--;;status,
                    --palette--;;timeRestriction,
                --div--;core.form.tabs:notes,
                    description,
                --div--;core.form.tabs:extended,
            ',
            'creationOptions' => [
                'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.types.admin',
            ],
        ],
    ],
];

?>
