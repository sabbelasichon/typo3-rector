<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector\Fixture;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'new_field', '', 'after:a');
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector\Fixture;

?>
