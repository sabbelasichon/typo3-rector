<?php

$_EXTKEY = 'key';

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 CMS Backend Styleguide and Testing use cases',
    'description' => 'Presents most supported styles for TYPO3 backend modules. Mocks typography, tables, forms, buttons, flash messages and helpers. More at https://github.com/TYPO3/styleguide',
    'category' => 'plugin',
    'author' => 'TYPO3 Core Team',
    'author_email' => 'typo3cms@typo3.org',
    'state' => 'stable',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '11.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '1.0.0',
            'gridelements' => '11.0.0-11.99.99',
        ],
        'conflicts' => [
            'extbase' => '1.0.0',
        ],
        'suggests' => [],
    ],
    'autoload' => [
        'classmap' => ['Classes', 'a-class.php'],
        'psr-4' => [
            'Vendor\\ExtName\\' => 'Classes',
        ],
    ],
    'autoload-dev' => [
        'psr-4' => [
            'Vendor\\ExtName\\Tests' => 'Tests',
        ],
    ],
];
