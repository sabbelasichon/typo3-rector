<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveMaxDBListItemsRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [],
    'interface' => [
        'maxDBListItems' => 'foo',
        'maxSingleDBListItems' => 'foo',
        'somethingElse' => '',
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveMaxDBListItemsRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [],
    'interface' => [
        'somethingElse' => '',
    ],
];

?>
