<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\RenameTableOptionsAndCollateConnectionConfigurationRector\Fixture;

$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions']['collate'] = 'utf8mb4_unicode_ci';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions'] = [
    'collate' => 'latin1_swedish_ci',
    'engine' => 'InnoDB',
];

$connectionName = 'MyConnection';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections'][$connectionName]['tableoptions']['collate'] = 'utf8_general_ci';
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\RenameTableOptionsAndCollateConnectionConfigurationRector\Fixture;

$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['defaultTableOptions']['collation'] = 'utf8mb4_unicode_ci';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['defaultTableOptions'] = [
    'collation' => 'latin1_swedish_ci',
    'engine' => 'InnoDB',
];

$connectionName = 'MyConnection';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections'][$connectionName]['defaultTableOptions']['collation'] = 'utf8_general_ci';
?>
