<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateSysRedirectDefaultTypeRector\Fixture;

$GLOBALS['TCA']['sys_redirect']['types']['1'] = [
    'label' => 'My custom label',
    'showitem' => 'custom_field',
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateSysRedirectDefaultTypeRector\Fixture;

$GLOBALS['TCA']['sys_redirect']['types']['default'] = [
    'label' => 'My custom label',
    'showitem' => 'custom_field',
];
?>
