<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v4\ExtensionManagementUtilityExtRelPath\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$relPath = ExtensionManagementUtility::extRelPath('my_extension');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v4\ExtensionManagementUtilityExtRelPath\Fixture;

use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$relPath = PathUtility::getAbsoluteWebPath(ExtensionManagementUtility::extPath('my_extension'));

?>
