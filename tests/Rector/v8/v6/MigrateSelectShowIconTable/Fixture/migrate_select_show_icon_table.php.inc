<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\MigrateSelectShowIconTable\Fixture;

return [
    'ctrl' => [
    ],
    'columns' => [
        'foo' => [
            'config' => [
                'type' => 'select',
                'items' => [
                    ['foo 1', 'foo1', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                    ['foo 2', 'foo2', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                ],
                'renderType' => 'selectSingle',
                'selicon_cols' => 16,
                'showIconTable' => true,
            ],
        ],
        'bar' => [
            'config' => [
                'type' => 'select',
                'items' => [
                    ['foo 1', 'foo1', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                    ['foo 2', 'foo2', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                ],
                'renderType' => 'selectSingle',
                'selicon_cols' => 16,
                'showIconTable' => false,
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\MigrateSelectShowIconTable\Fixture;

return [
    'ctrl' => [
    ],
    'columns' => [
        'foo' => [
            'config' => [
                'type' => 'select',
                'items' => [
                    ['foo 1', 'foo1', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                    ['foo 2', 'foo2', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                ],
                'renderType' => 'selectSingle',
                'fieldWizard' => ['selectIcons' => ['disabled' => false]],
            ],
        ],
        'bar' => [
            'config' => [
                'type' => 'select',
                'items' => [
                    ['foo 1', 'foo1', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                    ['foo 2', 'foo2', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                ],
                'renderType' => 'selectSingle',
            ],
        ],
    ],
];

?>
