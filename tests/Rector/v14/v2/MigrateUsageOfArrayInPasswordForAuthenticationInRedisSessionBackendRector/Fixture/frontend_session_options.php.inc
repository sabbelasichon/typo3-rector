<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateUsageOfArrayInPasswordForAuthenticationInRedisSessionBackendRector\Fixture;

use TYPO3\CMS\Core\Session\Backend\RedisSessionBackend;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['FE']['backend'] = RedisSessionBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['FE']['options'] = [
    'hostname' => 'redis',
    'password' => [
        'user' => 'redis',
        'pass' => 'secret',
    ],
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['BE']['options'] = [
    'password' => [
        'pass' => 'secret',
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateUsageOfArrayInPasswordForAuthenticationInRedisSessionBackendRector\Fixture;

use TYPO3\CMS\Core\Session\Backend\RedisSessionBackend;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['FE']['backend'] = RedisSessionBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['FE']['options'] = [
    'hostname' => 'redis',
    'password' => 'secret',
    'username' => 'redis',
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['BE']['options'] = [
    'password' => 'secret',
];

?>
