<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateToEmailTypeRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'email_field' => [
            'label' => 'Email',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,email',
                'max' => 255,
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateToEmailTypeRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'email_field' => [
            'label' => 'Email',
            'config' => ['type' => 'email'],
        ],
    ],
];

?>
