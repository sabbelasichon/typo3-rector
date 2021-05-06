<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'group_db_8' => [
            'label' => 'group_db_8',
            'config' => [
                'type' => 'otherType',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
                'wizards' => [
                    '_POSITION' => 'top',
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'pidList' => 42,
                        ],
                    ],
                ],
            ],
        ],
    ],
];

?>
