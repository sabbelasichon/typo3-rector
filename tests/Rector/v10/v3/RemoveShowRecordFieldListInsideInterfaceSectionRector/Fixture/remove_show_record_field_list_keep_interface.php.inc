<?php

return [
    'ctrl' => [
    ],
    'interface' => [
        'showRecordFieldList' => 'foo,bar,baz',
        'some' => 'foo,bar,baz',
    ],
    'columns' => [
    ],
];

?>
-----
<?php

return [
    'ctrl' => [
    ],
    'interface' => [
        'some' => 'foo,bar,baz',
    ],
    'columns' => [
    ],
];

?>
