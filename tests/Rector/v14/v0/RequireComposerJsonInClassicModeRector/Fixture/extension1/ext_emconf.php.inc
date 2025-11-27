<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RequireComposerJsonInClassicModeRector\Fixture;

$EM_CONF[$_EXTKEY] = [
    'title' => 'The extension title',
    'description' => 'A description about the extension',
    'version' => '12.0.8',
    'state' => 'stable',
    'category' => 'plugin',
    'author' => 'Max Mustermann',
    'author_email' => 'info@example.com',
    'author_company' => 'My GmbH',
    'constraints' => [
        'depends' => [
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
            'MyVendor\\MyExtension\\' => 'Classes/',
        ],
    ],
];
