<?php

return [
    'ctrl' => [
        'requestUpdate' => 'my_field'
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

return ['ctrl' => [], 'columns' => ['my_field' => ['config' => ['type' => 'input'], 'onChange' => 'reload']]];

?>
