<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateUsageOfArrayInPasswordForAuthenticationInRedisSessionBackendRector\Fixture;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['XY']['options'] = [
    'password' => [
        'user' => 'redis',
        'pass' => 'secret',
    ],
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['BE']['foo'] = [
    'password' => [
        'user' => 'redis',
        'pass' => 'secret',
    ],
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['session']['BE']['options']['password'] = [
    'user' => 'redis',
    'pass' => 'secret',
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['BE']['options'] = [
    'password' => [
        'user' => 'redis',
        'pass' => 'secret',
    ],
];
$config['TYPO3_CONF_VARS']['SYS']['session']['BE']['options'] = [
    'password' => [
        'user' => 'redis',
        'pass' => 'secret',
    ],
];

?>
