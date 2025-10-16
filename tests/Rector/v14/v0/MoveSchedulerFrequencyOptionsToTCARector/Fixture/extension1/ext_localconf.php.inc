<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTSConfigToUserTsConfigFileRector\Fixture;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['frequencyOptions']['0 2 * * *'] = 'LLL:EXT:my_extension/Resources/Private/Language/locallang.xlf:daily_2am';
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTSConfigToUserTsConfigFileRector\Fixture;

?>
