<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTSConfigToUserTsConfigFileRector\Fixture;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\TYPO3\CMS\Scheduler\Task\IpAnonymizationTask::class]['options']['tables'] = [
    'my_table' => [
        'dateField' => 'tstamp',
        'ipField' => 'private_ip',
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTSConfigToUserTsConfigFileRector\Fixture;

?>
