<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRector\Fixture;

return [
    'types' => [
        '0' => ['showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                title,
                stagechg_notification,
                --palette--;;memberlist,
            --div--;LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.internal_stages,
            --palette--;LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.notification_settings,
                --palette--;;stage.edit, edit_notification_defaults,
                --palette--;;stage.publish, publish_notification_defaults,
                --palette--;;stage.execute, execute_notification_defaults,
            --div--;LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.custom_stages,
                custom_stages,
            --div--;LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.mountpoints,
                db_mountpoints,file_mountpoints,
            --div--;LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.publish_access,
                previewlink_lifetime,live_edit,publish_access,publish_time,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                description,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRector\Fixture;

return [
    'types' => [
        '0' => ['showitem' => '
            --div--;core.form.tabs:general,
                title,
                stagechg_notification,
                --palette--;;memberlist,
            --div--;workspaces.db:tabs.internal_stages,
            --palette--;workspaces.db:tabs.notification_settings,
                --palette--;;stage.edit, edit_notification_defaults,
                --palette--;;stage.publish, publish_notification_defaults,
                --palette--;;stage.execute, execute_notification_defaults,
            --div--;workspaces.db:tabs.custom_stages,
                custom_stages,
            --div--;workspaces.db:tabs.mountpoints,
                db_mountpoints,file_mountpoints,
            --div--;workspaces.db:tabs.publish_access,
                previewlink_lifetime,live_edit,publish_access,publish_time,
            --div--;core.form.tabs:notes,
                description,
            --div--;core.form.tabs:extended,
        '],
    ],
];

?>
