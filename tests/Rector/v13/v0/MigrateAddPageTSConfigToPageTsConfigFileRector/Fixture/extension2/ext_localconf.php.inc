<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddPageTSConfigToPageTsConfigFileRector\Fixture;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '@import "EXT:ext_key/Configuration/TSconfig/*/*.tsconfig"'
);

$variable = 'ext_key';

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddPageTSConfigToPageTsConfigFileRector\Fixture;

$variable = 'ext_key';

?>
