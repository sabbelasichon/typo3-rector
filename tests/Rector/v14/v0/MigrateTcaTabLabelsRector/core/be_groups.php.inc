<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

return [
    'types' => [
        '0' => ['showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                title, subgroup,
                --palette--;;authentication,
            --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.record_permissions,
                --palette--;;permissionGeneral,
                --palette--;;permissionSpecific,
                --palette--;;permissionLanguages,
            --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.module_permissions,
                groupMods, custom_options, workspace_perms,
            --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.mounts_and_workspaces,
                db_mountpoints, file_mountpoints, file_permissions, category_perms,
            --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.options,
                TSconfig,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                hidden,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                description,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

return [
    'types' => [
        '0' => ['showitem' => '
            --div--;core.form.tabs:general,
                title, subgroup,
                --palette--;;authentication,
            --div--;core.form.tabs:recordpermissions,
                --palette--;;permissionGeneral,
                --palette--;;permissionSpecific,
                --palette--;;permissionLanguages,
            --div--;core.form.tabs:modulepermissions,
                groupMods, custom_options, workspace_perms,
            --div--;core.form.tabs:mounts,
                db_mountpoints, file_mountpoints, file_permissions, category_perms,
            --div--;core.form.tabs:options,
                TSconfig,
            --div--;core.form.tabs:access,
                hidden,
            --div--;core.form.tabs:notes,
                description,
            --div--;core.form.tabs:extended,
        '],
    ],
];

?>
