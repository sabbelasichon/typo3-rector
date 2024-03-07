<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'falFileRelation' => [
            'label' => 'Fal File Relation',
            'description' => 'Enter Description',
            'exclude' => 0,
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_table',
                'foreign_field' => 'foreignfield',
                'maxitems' => 1,
                'appearance' => [
                    'collapseAll' => true,
                    'useSortable' => true,
                    'showSynchronizationLink' => false,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => false,
                    'showRemovedLocalizationRecords' => false,
                    'expandSingle' => true,
                    'enabledControls' => [
                        'localize' => true,
                    ],
                ],
                'behaviour' => [
                    'mode' => 'select',
                ],
            ],
        ],
    ],
];

?>
-----
<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'falFileRelation' => [
            'label' => 'Fal File Relation',
            'description' => 'Enter Description',
            'exclude' => 0,
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_table',
                'foreign_field' => 'foreignfield',
                'maxitems' => 1,
                'appearance' => [
                    'collapseAll' => true,
                    'useSortable' => true,
                    'showSynchronizationLink' => false,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => false,
                    'expandSingle' => true,
                    'enabledControls' => [
                        'localize' => true,
                    ],
                ],
                'behaviour' => [
                    'mode' => 'select',
                ],
            ],
        ],
    ],
];

?>
