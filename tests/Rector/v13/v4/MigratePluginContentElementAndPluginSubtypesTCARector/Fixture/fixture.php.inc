<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesTCARector\Fixture;

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['my_plugin'] = 'pi_flexform';

$pluginSignature = 'tx_rector_pi1';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

$GLOBALS['TCA']['tt_content']['types']['list']['leave_me_alone']['my_plugin'] = 'pi_flexform';
$GLOBALS['TCA']['pages']['types']['list']['leave_me_alone']['my_plugin'] = 'pi_flexform';

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesTCARector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;Configuration,pi_flexform,', 'my_plugin', 'after:subheader');

$pluginSignature = 'tx_rector_pi1';
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;Configuration,pi_flexform,', $pluginSignature, 'after:subheader');

$GLOBALS['TCA']['tt_content']['types']['list']['leave_me_alone']['my_plugin'] = 'pi_flexform';
$GLOBALS['TCA']['pages']['types']['list']['leave_me_alone']['my_plugin'] = 'pi_flexform';

?>
