<?php

return [
    'ctrl' => [
        'requestUpdate' => 'foo, bar',
    ],
    'columns' => [
        'foo' => [],
        'bar' => [],
        'baz' => [],
    ]
];

?>
-----
<?php

return [
    'ctrl' => [],
    'columns' => [
        'foo' => ['onChange' => 'reload'],
        'bar' => ['onChange' => 'reload'],
        'baz' => [],
    ]
];

?>
