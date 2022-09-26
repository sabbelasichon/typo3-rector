<?php

$EM_CONF['foo_bar_baz'] = [
    'title' => 'TYPO3 with Webpack Encore',
    'description' => 'Webpack Encore from Symfony for TYPO3',
    'category' => 'fe',
    'author' => 'Sebastian Schreiber',
    'author_email' => 'breakpoint@schreibersebastian.de',
    'state' => 'stable',
    'version' => '3.0.4',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.2-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => ['Ssch\\Typo3Encore\\' => 'Classes']
    ],
];
