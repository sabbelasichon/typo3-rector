<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTsConfigRector\Fixture;

$boot = function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
        '@import \'EXT:testpackage/Configuration/TsConfig/User/user.tsconfig\''
    );
};

$boot();
unset($boot);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTsConfigRector\Fixture;

$boot = function () {
};

$boot();
unset($boot);

?>
