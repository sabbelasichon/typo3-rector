<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\MigrateLastPiecesOfDefaultExtrasRector\Fixture;

final class MigrateLastPiecesOfDefaultExtras
{
    public function run()
    {
        return [
            'ctrl' => [],
            'columns' => [
                'constants' => [
                    'label' => 'Foo',
                    'config' => [
                        'type' => 'text',
                        'cols' => 48,
                        'rows' => 15,
                    ],
                    'defaultExtras' => 'rte_only:nowrap:enable-tab:fixed-font'
                ],
                'foo' => [
                    'label' => 'Foo',
                    'config' => [
                        'type' => 'text',
                        'cols' => 48,
                        'rows' => 15,
                    ],
                    'defaultExtras' => 'rte_only'
                ],
            ],
            'types' => [
                'myType' => [
                    'columnsOverrides' => [
                        'constants' => [
                            'label' => 'Foo',
                            'config' => [
                                'type' => 'text',
                                'cols' => 48,
                                'rows' => 15,
                            ],
                            'defaultExtras' => 'rte_only:nowrap:enable-tab:fixed-font'
                        ],
                        'foo' => [
                            'label' => 'Foo',
                            'config' => [
                                'type' => 'text',
                                'cols' => 48,
                                'rows' => 15,
                            ],
                            'defaultExtras' => 'rte_only'
                        ],
                    ],
                ],
            ],
        ];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\MigrateLastPiecesOfDefaultExtrasRector\Fixture;

final class MigrateLastPiecesOfDefaultExtras
{
    public function run()
    {
        return [
            'ctrl' => [],
            'columns' => [
                'constants' => [
                    'label' => 'Foo',
                    'config' => [
                        'type' => 'text',
                        'cols' => 48,
                        'rows' => 15,
                        'wrap' => 'off',
                        'enableTabulator' => true,
                        'fixedFont' => true,
                    ]
                ],
                'foo' => [
                    'label' => 'Foo',
                    'config' => [
                        'type' => 'text',
                        'cols' => 48,
                        'rows' => 15,
                    ]
                ],
            ],
            'types' => [
                'myType' => [
                    'columnsOverrides' => [
                        'constants' => [
                            'label' => 'Foo',
                            'config' => [
                                'type' => 'text',
                                'cols' => 48,
                                'rows' => 15,
                                'wrap' => 'off',
                                'enableTabulator' => true,
                                'fixedFont' => true,
                            ]
                        ],
                        'foo' => [
                            'label' => 'Foo',
                            'config' => [
                                'type' => 'text',
                                'cols' => 48,
                                'rows' => 15,
                            ]
                        ],
                    ],
                ],
            ],
        ];
    }
}

?>
