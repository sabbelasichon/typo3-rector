<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionManagementUtilityAddStaticFileIntoTCAOverridesRector\Fixture;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('extension1', 'Configuration/TypoScript', 'Title');
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionManagementUtilityAddStaticFileIntoTCAOverridesRector\Fixture;

?>
