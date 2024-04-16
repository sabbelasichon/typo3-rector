<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector\Fixture;

$variable = 'tt_content';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes($variable, 'new_field', '', 'after:a');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'new_field', '', 'after:b');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('doing' . $variable . 'weirdStuff', 'new_field', '', 'after:c');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
$variable = 'tt_content';
ExtensionManagementUtility::addToAllTCAtypes('doing' . $variable . 'weirdStuff', 'new_field', '', 'after:c');

?>
