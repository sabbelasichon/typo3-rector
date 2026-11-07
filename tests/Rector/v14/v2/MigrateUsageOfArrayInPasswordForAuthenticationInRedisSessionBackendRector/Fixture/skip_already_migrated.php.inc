<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateUsageOfArrayInPasswordForAuthenticationInRedisSessionBackendRector\Fixture;

use TYPO3\CMS\Core\Session\Backend\RedisSessionBackend;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['BE'] = [
    'backend' => RedisSessionBackend::class,
    'options' => [
        'username' => 'redis',
        'password' => 'secret',
    ],
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['FE']['options'] = [
    'password' => 'secret',
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['FE']['options'] = [
    'password' => [
        'user' => 'redis',
    ],
];

?>
