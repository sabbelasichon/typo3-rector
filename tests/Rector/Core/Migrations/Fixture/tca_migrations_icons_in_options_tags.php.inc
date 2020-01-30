<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'my_field' => [
            'config' => [
                'type' => 'select',
                'iconsInOptionTags' => 1,
            ]
        ],
    ],
];

?>
-----
<?php

return ['ctrl' => [], 'columns' => ['my_field' => ['config' => ['type' => 'select', 'renderType' => 'selectSingle']]]];

?>
