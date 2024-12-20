<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateRequiredFlagRector\Fixture;

$GLOBALS['SiteConfiguration']['site']['columns']['required_column1'] = [
    'label' => 'a',
    'description' => '',
    'config' => [
        'type' => 'input',
        'eval' => 'trim,required',
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateRequiredFlagRector\Fixture;

$GLOBALS['SiteConfiguration']['site']['columns']['required_column1'] = [
    'label' => 'a',
    'description' => '',
    'config' => [
        'type' => 'input',
        'eval' => 'trim',
        'required' => true,
    ],
];
?>
