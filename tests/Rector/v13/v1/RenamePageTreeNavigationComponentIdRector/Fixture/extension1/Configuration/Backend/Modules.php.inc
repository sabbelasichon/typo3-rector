<?php

return [
    'mymodule' => [
        'parent' => 'web',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
    ],
];
?>
-----
<?php

return [
    'mymodule' => [
        'parent' => 'web',
        'navigationComponent' => '@typo3/backend/tree/page-tree-element',
    ],
];
?>
