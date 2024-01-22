<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateRequiredFlagRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'fullExample' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'format' => 'date',
                'required' => true,
                'readOnly' => true,
                'size' => 20,
                'max' => 1234,
                'eval' => 'trim,null,time,int',
            ],
        ],
        'a_date_field' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'required' => true,
                'size' => 20,
                'max' => 1024,
                'eval' => 'date,int',
                'default' => 0,
            ],
        ],
        'a_datetime_field' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'size' => 20,
                'required' => true,
            ],
        ],
        'a_timesec_field' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'timesec',
            ],
        ],
        'format_datetime' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'format' => 'datetime',
            ],
        ],
        'default_string_zero' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'default' => '0',
            ],
        ],
        'default_string_cast' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'default' => '9',
            ],
        ],
        'default_empty' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'default' => '',
            ],
        ],
        'default_empty_date' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'dbType' => 'date',
                'default' => '0000-00-00',
            ],
        ],
        'default_empty_datetime' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'dbType' => 'datetime',
                'default' => '0000-00-00 00:00:00',
            ],
        ],
        'default_empty_time' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'dbType' => 'time',
                'default' => '00:00:00',
            ],
        ],
        'aColumn' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => '0',
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => '',
            ],
        ],
        'cColumn' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => '16362836',
            ],
        ],
        'dColumn' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => 'invalid',
            ],
        ],
        'eColumn' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'default' => time(),
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateRequiredFlagRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'fullExample' => [
            'config' => [
                'type' => 'datetime',
                'required' => true,
                'readOnly' => true,
                'size' => 20,
                'format' => 'time',
                'eval' => 'null',
            ],
        ],
        'a_date_field' => [
            'config' => [
                'type' => 'datetime',
                'required' => true,
                'size' => 20,
                'default' => 0,
                'format' => 'date',
            ],
        ],
        'a_datetime_field' => [
            'config' => [
                'type' => 'datetime',
                'size' => 20,
                'required' => true,
            ],
        ],
        'a_timesec_field' => [
            'config' => [
                'type' => 'datetime',
                'format' => 'timesec',
            ],
        ],
        'format_datetime' => [
            'config' => [
                'type' => 'datetime',
            ],
        ],
        'default_string_zero' => [
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'default_string_cast' => [
            'config' => [
                'type' => 'datetime',
                'default' => 9,
            ],
        ],
        'default_empty' => [
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'default_empty_date' => [
            'config' => [
                'type' => 'datetime',
                'dbType' => 'date',
            ],
        ],
        'default_empty_datetime' => [
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
            ],
        ],
        'default_empty_time' => [
            'config' => [
                'type' => 'datetime',
                'dbType' => 'time',
            ],
        ],
        'aColumn' => [
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'cColumn' => [
            'config' => [
                'type' => 'datetime',
                'default' => 16362836,
            ],
        ],
        'dColumn' => [
            'config' => [
                'type' => 'datetime',
            ],
        ],
        'eColumn' => [
            'config' => [
                'type' => 'datetime',
                'default' => time(),
            ],
        ],
    ],
];

?>
