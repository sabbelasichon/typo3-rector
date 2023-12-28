<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigratePasswordAndSaltedPasswordToPasswordTypeRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'input',
                'eval' => 'trim,password,saltedPassword',
                'required' => true,
            ],
        ],
        'aColumnWithPassword' => [
            'config' => [
                'type' => 'input',
                'eval' => 'trim,password',
                'required' => true,
            ],
        ],
        'aColumnWithSaltedPassword' => [
            'config' => [
                'type' => 'input',
                'eval' => 'trim,saltedPassword',
                'required' => true,
            ],
        ],
        'fullMigration' => [
            'config' => [
                'type' => 'input',
                'eval' => 'trim,password,saltedPassword,null,int',
                'required' => true,
                'max' => 1234,
                'search' => [
                    'andWhere' => '{#CType}=\'text\' OR {#CType}=\'textpic\' OR {#CType}=\'textmedia\'',
                ],
            ],
        ],
        'differentColumn' => [
            'config' => [
                'type' => 'input',
                'eval' => 'trim,unique',
            ],
        ],
        'wrongTypeColumn' => [
            'config' => [
                'type' => 'text',
                'eval' => 'trim,password,saltedPassword',
            ],
        ],
        'alreadyMigratedColumn' => [
            'config' => [
                'type' => 'password',
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigratePasswordAndSaltedPasswordToPasswordTypeRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'password',
                'required' => true,
            ],
        ],
        'aColumnWithPassword' => [
            'config' => [
                'type' => 'password',
                'required' => true,
                'hashed' => false,
            ],
        ],
        'aColumnWithSaltedPassword' => [
            'config' => [
                'type' => 'password',
                'required' => true,
            ],
        ],
        'fullMigration' => [
            'config' => [
                'type' => 'password',
                'eval' => 'null',
                'required' => true,
            ],
        ],
        'differentColumn' => [
            'config' => [
                'type' => 'input',
                'eval' => 'trim,unique',
            ],
        ],
        'wrongTypeColumn' => [
            'config' => [
                'type' => 'text',
                'eval' => 'trim,password,saltedPassword',
            ],
        ],
        'alreadyMigratedColumn' => [
            'config' => [
                'type' => 'password',
            ],
        ],
    ],
];

?>
