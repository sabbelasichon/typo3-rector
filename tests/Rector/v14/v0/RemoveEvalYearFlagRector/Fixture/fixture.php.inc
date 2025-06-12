<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveEvalYearFlagRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'year_column1' => [
            'config' => [
                'eval' => 'year,trim',
            ],
        ],
        'year_column2' => [
            'config' => [
                'eval' => 'year',
            ],
        ],
        'employment_type' => [
            'config' => [
                'eval' => ''
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveEvalYearFlagRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'year_column1' => [
            'config' => [
                'eval' => 'trim',
            ],
        ],
        'year_column2' => [
            'config' => [],
        ],
        'employment_type' => [
            'config' => [
                'eval' => ''
            ],
        ],
    ],
];

?>
