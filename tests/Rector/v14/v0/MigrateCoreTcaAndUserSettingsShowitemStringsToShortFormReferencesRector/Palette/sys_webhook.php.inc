<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

return [
    'palettes' => [
        'config' => [
            'label' => 'LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.config',
            'description' => 'LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.config.description',
            'showitem' => 'webhook_type, identifier, --linebreak--, name, description, --linebreak--, url, secret',
        ],
        'http_settings' => [
            'label' => 'LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.http_settings',
            'description' => 'LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.http_settings.description',
            'showitem' => 'method, verify_ssl, --linebreak--, additional_headers',
        ],
        'access' => [
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.access',
            'showitem' => 'disabled, starttime, endtime',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

return [
    'palettes' => [
        'config' => [
            'label' => 'webhooks.db:palette.config',
            'description' => 'webhooks.db:palette.config.description',
            'showitem' => 'webhook_type, identifier, --linebreak--, name, description, --linebreak--, url, secret',
        ],
        'http_settings' => [
            'label' => 'webhooks.db:palette.http_settings',
            'description' => 'webhooks.db:palette.http_settings.description',
            'showitem' => 'method, verify_ssl, --linebreak--, additional_headers',
        ],
        'access' => [
            'label' => 'core.form.palettes:access',
            'showitem' => 'disabled, starttime, endtime',
        ],
    ],
];

?>
