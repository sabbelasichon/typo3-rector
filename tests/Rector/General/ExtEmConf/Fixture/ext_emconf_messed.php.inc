<?php

namespace Ssch\TYPO3Rector\Tests\Rector\General\ExtEmConf\Fixture;

$EM_CONF = [];
$_EXTKEY = 'foo';

$EM_CONF[$_EXTKEY] = [
    'title' => 'Package Extension',
    'description' => 'Package Extension',
    'category' => 'fe',
    'shy' => 0,
    'version' => '2.0.1',
    'dependencies' => '',
    'conflicts' => '',
    'priority' => '',
    'loadOrder' => '',
    'module' => '',
    'state' => 'stable',
    'uploadfolder' => 1,
    'createDirs' => '',
    'modify_tables' => '',
    'clearcacheonload' => 0,
    'lockType' => '',
    'author' => 'Max Mustrmann',
    'author_email' => 'max.mustermann@mustermann.de',
    'author_company' => 'Mustermann GmbH',
    'CGLcompliance' => '',
    'CGLcompliance_note' => '',
    'constraints' => [
        'depends' => [
            'php' => '5.6.0-0.0.0',
            'typo3' => '7.6.0-8.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' =>
        [
            'psr-4' =>
                [
                    'Foo\\Bar\\' => 'Classes/',
                ],
        ],
    '_md5_values_when_last_written' => 'a:0:{}',
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\General\ExtEmConf\Fixture;

$EM_CONF = [];
$_EXTKEY = 'foo';

$EM_CONF[$_EXTKEY] = [
    'title' => 'Package Extension',
    'description' => 'Package Extension',
    'category' => 'fe',
    'version' => '2.0.1',
    'state' => 'stable',
    'author' => 'Max Mustrmann',
    'author_email' => 'max.mustermann@mustermann.de',
    'author_company' => 'Mustermann GmbH',
    'constraints' => [
        'depends' => [
            'php' => '5.6.0-0.0.0',
            'typo3' => '9.5.0-10.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' =>
        [
            'psr-4' =>
                [
                    'Foo\\Bar\\' => 'Classes/',
                ],
        ],
    '_md5_values_when_last_written' => 'a:0:{}',
];

?>
