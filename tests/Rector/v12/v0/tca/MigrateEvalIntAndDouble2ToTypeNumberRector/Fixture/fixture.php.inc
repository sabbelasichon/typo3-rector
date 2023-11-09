<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateEvalIntAndDouble2ToTypeNumberRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'int_field' => [
            'label' => 'int field',
            'config' => [
                'type' => 'input',
                'eval' => 'int',
            ],
        ],
        'nullable_int_field' => [
            'label' => 'Nullable int field',
            'config' => [
                'type' => 'input',
                'eval' => 'int,null',
            ],
        ],
        'double2_field' => [
            'label' => 'double2 field',
            'config' => [
                'type' => 'input',
                'eval' => 'double2',
            ],
        ],
        'already_number_field' => [
            'label' => 'int field',
            'config' => [
                'type' => 'number'
            ],
        ],
        'already_decimal_number_field' => [
            'label' => 'double2 field',
            'config' => [
                'type' => 'number',
                'format' => 'decimal'
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateEvalIntAndDouble2ToTypeNumberRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'int_field' => [
            'label' => 'int field',
            'config' => [
                'type' => 'number',
            ],
        ],
        'nullable_int_field' => [
            'label' => 'Nullable int field',
            'config' => [
                'type' => 'number',
                'eval' => 'null',
            ],
        ],
        'double2_field' => [
            'label' => 'double2 field',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
            ],
        ],
        'already_number_field' => [
            'label' => 'int field',
            'config' => [
                'type' => 'number'
            ],
        ],
        'already_decimal_number_field' => [
            'label' => 'double2 field',
            'config' => [
                'type' => 'number',
                'format' => 'decimal'
            ],
        ],
    ],
];

?>
