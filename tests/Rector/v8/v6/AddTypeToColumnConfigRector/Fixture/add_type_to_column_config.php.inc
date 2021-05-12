<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'ctrl' => [],
    'columns' => [
        'foo' => [
            'config' => [
            ],
        ],
        'bar' => [
        ],
        'baz' => [
            'config' => [
                'type' => 'select'
            ]
        ],
        'thumbnail' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:example/Resources/Private/Language/locallang_db.xlf:tx_example_domain_model_episode.thumbnail',
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig('thumbnail', [
                'appearance' => [
                    'elementBrowserAllowed' => 'pdf',
                    'createNewRelationLinkTitle' => 'LLL:EXT:example/Resources/Private/Language/locallang_db.xlf:tx_example_domain_model_episode.thumbnail.addFileReference'
                ],
                'minitems' => '1',
                'maxitems' => '1'
            ])
        ],
    ]
];

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'ctrl' => [],
    'columns' => [
        'foo' => [
            'config' => ['type' => 'none'],
        ],
        'bar' => ['config' => ['type' => 'none']],
        'baz' => [
            'config' => [
                'type' => 'select'
            ]
        ],
        'thumbnail' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:example/Resources/Private/Language/locallang_db.xlf:tx_example_domain_model_episode.thumbnail',
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig('thumbnail', [
                'appearance' => [
                    'elementBrowserAllowed' => 'pdf',
                    'createNewRelationLinkTitle' => 'LLL:EXT:example/Resources/Private/Language/locallang_db.xlf:tx_example_domain_model_episode.thumbnail.addFileReference'
                ],
                'minitems' => '1',
                'maxitems' => '1'
            ])
        ],
    ]
];

?>
