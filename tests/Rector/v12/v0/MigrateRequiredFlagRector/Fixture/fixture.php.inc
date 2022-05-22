<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateRequiredFlagRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'required_column1' => [
            'config' => [
                'eval' => 'trim,required',
            ],
        ],
        'required_column2' => [
            'config' => [
                'eval' => 'required',
            ],
        ],
        'already_required_column' => [
            'config' => [
                'eval' => 'required',
                'required' => true,
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateRequiredFlagRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'required_column1' => [
            'config' => [
                'eval' => 'trim', 'required' => true,
            ],
        ],
        'required_column2' => [
            'config' => [
                'required' => true,
            ],
        ],
        'already_required_column' => [
            'config' => ['required' => true],
        ],
    ],
];

?>
