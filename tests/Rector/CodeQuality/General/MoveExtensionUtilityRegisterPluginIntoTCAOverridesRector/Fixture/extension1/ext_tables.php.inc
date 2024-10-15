<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector\Fixture;

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('extension1', 'PluginName', 'My Title');
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector\Fixture;

?>
