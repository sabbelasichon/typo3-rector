<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MoveAllowTableOnStandardPagesToTCAConfigurationRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::allowTableOnStandardPages('tx_table_with_existing_tca_configuration_file');
ExtensionManagementUtility::allowTableOnStandardPages('tx_table_with_existing_tca_configuration_file_and_security_key');
ExtensionManagementUtility::allowTableOnStandardPages('tx_table_without_existing_tca_configuration_file');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MoveAllowTableOnStandardPagesToTCAConfigurationRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

?>
