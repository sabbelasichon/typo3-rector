<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTSConfigToUserTsConfigFileRector\Fixture;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    '@import "EXT:extension1/Configuration/TSconfig/one.tsconfig"'
);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTSConfigToUserTsConfigFileRector\Fixture;


?>
