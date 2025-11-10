<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateSysRedirectDefaultTypeRector\Fixture;

$GLOBALS['TCA']['sys_redirect']['types']['1']['label'] = 'My custom label';
$GLOBALS['TCA']['sys_redirect']['types']['1']['showitem'] = 'custom_field';
$GLOBALS['TCA']['sys_redirect']['types']['1']['customKey'] = 'customValue';
$GLOBALS['TCA']['sys_redirect']['types']['1']['customKey']['subkey'] = 'customSubValue';

$GLOBALS['TCA']['sys_redirect']['types']['2']['label'] = 'leave me alone!';
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateSysRedirectDefaultTypeRector\Fixture;

$GLOBALS['TCA']['sys_redirect']['types']['default']['label'] = 'My custom label';
$GLOBALS['TCA']['sys_redirect']['types']['default']['showitem'] = 'custom_field';
$GLOBALS['TCA']['sys_redirect']['types']['default']['customKey'] = 'customValue';
$GLOBALS['TCA']['sys_redirect']['types']['default']['customKey']['subkey'] = 'customSubValue';

$GLOBALS['TCA']['sys_redirect']['types']['2']['label'] = 'leave me alone!';
?>
