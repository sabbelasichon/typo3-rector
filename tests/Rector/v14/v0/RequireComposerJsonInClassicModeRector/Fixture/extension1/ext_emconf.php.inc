<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RequireComposerJsonInClassicModeRector\Fixture;

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 Rector',
    'description' => 'A description about the extension',
    'version' => '12.0.8',
    'state' => 'stable',
    'category' => 'plugin',
    'author' => 'Rafael Kaehm, Markus Friedrich',
    'author_email' => 'info@dkd.de',
    'author_company' => 'dkd Internet Service GmbH',
    'constraints' => [
        'depends' => [
            'scheduler' => '',
            'typo3' => '12.4.3-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'classmap' => [
            'Resources/Private/Php/',
        ],
        'psr-4' => [
            'ApacheSolrForTypo3\\Solr\\' => 'Classes/',
        ],
    ],
];
