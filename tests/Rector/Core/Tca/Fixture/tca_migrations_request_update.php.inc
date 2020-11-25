<?php

return [
    'ctrl' => [
        'requestUpdate' => 'my_field'
    ],
    'types' => [
        'aType' => [
            'showitem' => 'aField;aLabel;anAdditionalPaletteName',
        ],
    ],
    'columns' => [
        'my_field' => [
            'config' => [
                'type' => 'input',
            ]
        ],
    ],
];

?>
-----
<?php

return ['ctrl' => [], 'types' => ['aType' => ['showitem' => 'aField;aLabel,--palette--;;anAdditionalPaletteName']], 'columns' => ['my_field' => ['config' => ['type' => 'input'], 'onChange' => 'reload']]];

?>
