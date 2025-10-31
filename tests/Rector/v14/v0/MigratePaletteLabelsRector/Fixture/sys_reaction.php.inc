<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Fixture;

return [
    'palettes' => [
        'config' => [
            'label' => 'LLL:EXT:reactions/Resources/Private/Language/locallang_db.xlf:palette.config',
            'description' => 'LLL:EXT:reactions/Resources/Private/Language/locallang_db.xlf:palette.config.description',
            'showitem' => 'reaction_type, --linebreak--, name, description, --linebreak--, identifier, secret',
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

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Fixture;

return [
    'palettes' => [
        'config' => [
            'label' => 'reactions.db:palette.config',
            'description' => 'reactions.db:palette.config.description',
            'showitem' => 'reaction_type, --linebreak--, name, description, --linebreak--, identifier, secret',
        ],
        'access' => [
            'label' => 'core.form.palettes:access',
            'showitem' => 'disabled, starttime, endtime',
        ],
    ],
];

?>
