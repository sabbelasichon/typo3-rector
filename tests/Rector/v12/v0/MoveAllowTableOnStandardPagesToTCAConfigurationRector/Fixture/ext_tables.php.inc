<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MoveAllowTableOnStandardPagesToTCAConfigurationRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::allowTableOnStandardPages('tx_table_with_existing_tca_configuration_file');
ExtensionManagementUtility::allowTableOnStandardPages('tx_table_without_existing_tca_configuration_file');
ExtensionManagementUtility::allowTableOnStandardPages('tx_table_one,tx_table_two');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MoveAllowTableOnStandardPagesToTCAConfigurationRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

?>
