<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveFieldSearchConfigOptionsRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'my_field' => [
            'config' => [
                'type' => 'input',
                'search' => [
                    'case' => true,
                    'pidonly' => true,
                    'andWhere' => '{#CType}=\'text\'',
                ],
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveFieldSearchConfigOptionsRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'my_field' => [
            'config' => [
                'type' => 'input',
            ],
        ],
    ],
];

?>
