<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateUsageOfArrayInPasswordForAuthenticationInRedisSessionBackendRector\Fixture;

use TYPO3\CMS\Core\Session\Backend\RedisSessionBackend;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['BE'] = [
    'backend' => RedisSessionBackend::class,
    'options' => [
        'database' => 0,
        'hostname' => 'redis',
        'port' => 6379,
        'password' => [
            'user' => 'redis',
            'pass' => 'secret',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateUsageOfArrayInPasswordForAuthenticationInRedisSessionBackendRector\Fixture;

use TYPO3\CMS\Core\Session\Backend\RedisSessionBackend;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['BE'] = [
    'backend' => RedisSessionBackend::class,
    'options' => [
        'database' => 0,
        'hostname' => 'redis',
        'port' => 6379,
        'password' => 'secret',
        'username' => 'redis',
    ],
];

?>
