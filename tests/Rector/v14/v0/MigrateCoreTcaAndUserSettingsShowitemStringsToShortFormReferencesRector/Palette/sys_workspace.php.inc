<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

return [
    'palettes' => [
        'memberlist' => [
            'label' => 'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.users',
            'showitem' => 'adminusers,members',
        ],
        'stage.edit' => [
            'label' => 'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:sys_workspace.palette.stage.edit',
            'showitem' => 'edit_allow_notificaton_settings, edit_notification_preselection,',
        ],
        'stage.publish' => [
            'label' => 'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:sys_workspace.palette.stage.publish',
            'showitem' => 'publish_allow_notificaton_settings, publish_notification_preselection,',
        ],
        'stage.execute' => [
            'label' => 'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:sys_workspace.palette.stage.execute',
            'showitem' => 'execute_allow_notificaton_settings, execute_notification_preselection,',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

return [
    'palettes' => [
        'memberlist' => [
            'label' => 'workspaces.db:tabs.users',
            'showitem' => 'adminusers,members',
        ],
        'stage.edit' => [
            'label' => 'workspaces.db:sys_workspace.palette.stage.edit',
            'showitem' => 'edit_allow_notificaton_settings, edit_notification_preselection,',
        ],
        'stage.publish' => [
            'label' => 'workspaces.db:sys_workspace.palette.stage.publish',
            'showitem' => 'publish_allow_notificaton_settings, publish_notification_preselection,',
        ],
        'stage.execute' => [
            'label' => 'workspaces.db:sys_workspace.palette.stage.execute',
            'showitem' => 'execute_allow_notificaton_settings, execute_notification_preselection,',
        ],
    ],
];

?>
