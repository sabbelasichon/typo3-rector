<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddPageTSConfigToPageTsConfigFileRector\Fixture;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('extension1', 'Configuration/TypoScript', 'Title');
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddPageTSConfigToPageTsConfigFileRector\Fixture;

?>
