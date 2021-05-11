<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$new_columns = [
    'bar' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:offers/Resources/Private/Language/locallang_db.xlf:tx_example_domain_model_foo.bar',
        'config' => [
        ],
    ],
    'baz' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:offers/Resources/Private/Language/locallang_db.xlf:tx_example_domain_model_foo.baz',
    ],
    'correct' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:offers/Resources/Private/Language/locallang_db.xlf:tx_example_domain_model_foo.baz',
        'config' => [
            'type' => 'text',
        ]
    ],
];

ExtensionManagementUtility::addTCAcolumns('tx_example_domain_model_foo', $new_columns);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$new_columns = [
    'bar' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:offers/Resources/Private/Language/locallang_db.xlf:tx_example_domain_model_foo.bar',
        'config' => ['type' => 'none'],
    ],
    'baz' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:offers/Resources/Private/Language/locallang_db.xlf:tx_example_domain_model_foo.baz',
        'config' => ['type' => 'none'],
    ],
    'correct' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:offers/Resources/Private/Language/locallang_db.xlf:tx_example_domain_model_foo.baz',
        'config' => [
            'type' => 'text',
        ]
    ],
];

ExtensionManagementUtility::addTCAcolumns('tx_example_domain_model_foo', $new_columns);

?>
