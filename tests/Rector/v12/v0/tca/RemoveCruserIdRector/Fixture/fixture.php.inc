<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\RemoveCruserIdRector\Fixture;

return [
    'ctrl' => [
        'label' => 'foo',
        'cruser_id' => 'cruser_id',
    ],
    'columns' => [
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\RemoveCruserIdRector\Fixture;

return [
    'ctrl' => [
        'label' => 'foo',
    ],
    'columns' => [
    ],
];

?>
