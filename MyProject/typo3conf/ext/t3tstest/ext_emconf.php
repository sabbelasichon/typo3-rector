<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TS Test',
    'description' => '',
    'category' => 'plugin',
    'author' => 'test',
    'author_email' => 'test',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0^',
    'constraints' => [
        'depends' => [
            'typo3'     => '11.5.1-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
