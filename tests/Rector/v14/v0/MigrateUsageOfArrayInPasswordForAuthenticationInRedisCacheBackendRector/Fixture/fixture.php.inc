<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateUsageOfArrayInPasswordForAuthenticationInRedisCacheBackendRector\Fixture;

use TYPO3\CMS\Core\Cache\Backend\RedisBackend;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['pages']['backend'] = RedisBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['pages']['options'] = [
    'defaultLifetime' => 86400,
    'database' => 0,
    'hostname' => 'redis',
    'port' => 6379,
    'password' => [
        'user' => 'redis',
        'pass' => 'redis',
    ]
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tt_content']['backend'] = RedisBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tt_content']['options'] = [
    'password' => [
        'pass' => 'redis',
    ]
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['already_migrated']['backend'] = RedisBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['already_migrated']['options'] = [
    'defaultLifetime' => 86400,
    'database' => 0,
    'hostname' => 'redis',
    'port' => 6379,
    'password' => 'redis',
    'username' => 'redis'
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateUsageOfArrayInPasswordForAuthenticationInRedisCacheBackendRector\Fixture;

use TYPO3\CMS\Core\Cache\Backend\RedisBackend;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['pages']['backend'] = RedisBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['pages']['options'] = [
    'defaultLifetime' => 86400,
    'database' => 0,
    'hostname' => 'redis',
    'port' => 6379,
    'password' => 'redis',
    'username' => 'redis'
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tt_content']['backend'] = RedisBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tt_content']['options'] = [
    'password' => 'redis'
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['already_migrated']['backend'] = RedisBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['already_migrated']['options'] = [
    'defaultLifetime' => 86400,
    'database' => 0,
    'hostname' => 'redis',
    'port' => 6379,
    'password' => 'redis',
    'username' => 'redis'
];

?>
