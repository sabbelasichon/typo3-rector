<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'my_date' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'max' => 20
            ],
        ],
    ],
];

?>
-----
<?php

return ['ctrl' => [], 'columns' => ['my_date' => ['config' => ['type' => 'input', 'renderType' => 'inputDateTime']]]];

?>
