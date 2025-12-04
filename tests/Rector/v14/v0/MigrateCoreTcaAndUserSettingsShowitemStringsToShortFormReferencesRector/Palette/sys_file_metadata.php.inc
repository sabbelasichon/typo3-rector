<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

return [
    'palettes' => [
        '20' => [
            'label' => 'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.accessibility',
            'showitem' => 'alternative, --linebreak--, caption, --linebreak--, download_name',
        ],
        '25' => [
            'label' => 'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.accessibility',
            'showitem' => 'caption, --linebreak--, download_name',
        ],
        '30' => [
            'label' => 'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.gps',
            'showitem' => 'latitude, longitude',
        ],
        '40' => [
            'label' => 'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.geo_location',
            'showitem' => 'location_country, location_region, location_city',
        ],
        '50' => [
            'label' => 'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.metrics',
            'showitem' => 'width, height, unit',
        ],
        '60' => [
            'label' => 'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.content_date',
            'showitem' => 'content_creation_date, content_modification_date',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

return [
    'palettes' => [
        '20' => [
            'label' => 'core.form.palettes:accessibility',
            'showitem' => 'alternative, --linebreak--, caption, --linebreak--, download_name',
        ],
        '25' => [
            'label' => 'core.form.palettes:accessibility',
            'showitem' => 'caption, --linebreak--, download_name',
        ],
        '30' => [
            'label' => 'core.form.palettes:gps',
            'showitem' => 'latitude, longitude',
        ],
        '40' => [
            'label' => 'core.form.palettes:geolocation',
            'showitem' => 'location_country, location_region, location_city',
        ],
        '50' => [
            'label' => 'core.form.palettes:metrics',
            'showitem' => 'width, height, unit',
        ],
        '60' => [
            'label' => 'core.form.palettes:contentdate',
            'showitem' => 'content_creation_date, content_modification_date',
        ],
    ],
];

?>
