<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v3\SoftReferencesFunctionalityRemovedRector\Fixture;

return [
    'ctrl' => [
    ],
    'columns' => [
        'TSconfig' => [
            'label' => 'TSconfig:',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '5',
                'softref' => 'TSconfig',
            ],
            'defaultExtras' => 'fixed-font : enable-tab',
        ],
        'TSconfig2' => [
            'label' => 'TSconfig:',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '5',
                'softref' => 'TSconfig,TStemplate',
            ],
            'defaultExtras' => 'fixed-font : enable-tab',
        ],
        'TSconfig3' => [
            'label' => 'TSconfig:',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '5',
                'softref' => 'TSconfig,TStemplate,foo',
            ],
            'defaultExtras' => 'fixed-font : enable-tab',
        ],
    ],
];

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v3\SoftReferencesFunctionalityRemovedRector\Fixture;

return [
    'ctrl' => [
    ],
    'columns' => [
        'TSconfig' => [
            'label' => 'TSconfig:',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '5',
            ],
            'defaultExtras' => 'fixed-font : enable-tab',
        ],
        'TSconfig2' => [
            'label' => 'TSconfig:',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '5',
            ],
            'defaultExtras' => 'fixed-font : enable-tab',
        ],
        'TSconfig3' => [
            'label' => 'TSconfig:',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '5',
                'softref' => 'foo',
            ],
            'defaultExtras' => 'fixed-font : enable-tab',
        ],
    ],
];

?>
