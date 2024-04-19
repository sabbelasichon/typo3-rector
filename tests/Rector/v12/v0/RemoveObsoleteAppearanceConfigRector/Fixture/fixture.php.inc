<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\RemoveObsoleteAppearanceConfigRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'random' => [
            'config' => [
                'type' => 'group',
                'appearance' => [
                    'elementBrowserType' => 'db',
                    'elementBrowserAllowed' => 'foo',
                ],
            ],
        ],
        'random-inline' => [
            'config' => [
                'type' => 'inline',
                'appearance' => [
                    'headerThumbnail' => 'db',
                    'fileUploadAllowed' => 'foo',
                    'fileByUrlAllowed' => 'foo',
                ],
            ],
        ],
        'random-inline-two' => [
            'config' => [
                'type' => 'inline',
                'appearance' => [
                    'headerThumbnail' => 'db',
                    'fileUploadAllowed' => 'foo',
                    'fileByUrlAllowed' => 'foo',
                    'collapseAll' => true
                ],
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\RemoveObsoleteAppearanceConfigRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'random' => [
            'config' => [
                'type' => 'group',
            ],
        ],
        'random-inline' => [
            'config' => [
                'type' => 'inline',
            ],
        ],
        'random-inline-two' => [
            'config' => [
                'type' => 'inline',
                'appearance' => [
                    'collapseAll' => true
                ],
            ],
        ],
    ],
];

?>
