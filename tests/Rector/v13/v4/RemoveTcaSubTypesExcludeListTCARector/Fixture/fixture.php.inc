<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\RemoveTcaSubTypesExcludeListTCARector\Fixture;

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['my_plugin'] = 'layout,select_key,pages';

$pluginSignature = 'tx_rector_pi1';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\RemoveTcaSubTypesExcludeListTCARector\Fixture;

$pluginSignature = 'tx_rector_pi1';

?>
