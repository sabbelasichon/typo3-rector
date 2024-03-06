<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveMmHasUidFieldRector\Fixture;

$GLOBALS['TCA']['table_example'] = [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'group',
                'MM_hasUidField' => true,
            ],
        ],
    ],
] ;
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveMmHasUidFieldRector\Fixture;

$GLOBALS['TCA']['table_example'] = [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'group',
            ],
        ],
    ],
] ;
?>
