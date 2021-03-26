<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\RemoveL10nModeNoCopy\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'ctrl' => [],
    'columns' => [
        'foo' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'Bar',
        ],
        'bar' => [
            'exclude' => 1,
            'l10n_mode' => 'noCopy',
            'label' => 'Bar',
        ],
        'baz' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'Bar',
            'config' => [
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ]
            ],
        ],
        'fizz' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'Bar',
            'config' => [
                'foo' => 'bar'
            ],
        ],
        'fuzz' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'Bar',
            'config' => [
                'behaviour' => [
                ]
            ],
        ],
        'image' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'Bar',
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig('pictures', [], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])
        ]
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\RemoveL10nModeNoCopy\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'ctrl' => [],
    'columns' => [
        'foo' => [
            'exclude' => 1,
            'label' => 'Bar',
            'config' => ['behaviour' => ['allowLanguageSynchronization' => true]],
        ],
        'bar' => [
            'exclude' => 1,
            'label' => 'Bar',
        ],
        'baz' => [
            'exclude' => 1,
            'label' => 'Bar',
            'config' => [
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'fizz' => [
            'exclude' => 1,
            'label' => 'Bar',
            'config' => [
                'foo' => 'bar', ['behaviour' => ['allowLanguageSynchronization' => true]]
            ],
        ],
        'fuzz' => [
            'exclude' => 1,
            'label' => 'Bar',
            'config' => [
                'behaviour' => ['allowLanguageSynchronization' => true]
            ],
        ],
        'image' => [
            'exclude' => 1,
            'label' => 'Bar',
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig('pictures', [], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])
        ]
    ],
];

?>
