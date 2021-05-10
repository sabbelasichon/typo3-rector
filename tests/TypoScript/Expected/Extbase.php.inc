<?php

declare(strict_types = 1);

return [
    'GeorgRinger\\News\\Domain\\Model\\News' => [
        'subclasses' => [
            'GeorgRinger\\News\\Domain\\Model\\NewsDefault',
            'GeorgRinger\\News\\Domain\\Model\\NewsInternal',
            'GeorgRinger\\News\\Domain\\Model\\NewsExternal',
        ],
    ],
    'GeorgRinger\\News\\Domain\\Model\\NewsDefault' => [
        'recordType' => '0',
        'tableName' => 'tx_news_domain_model_news',
    ],
    'GeorgRinger\\News\\Domain\\Model\\NewsInternal' => [
        'recordType' => '1',
        'tableName' => 'tx_news_domain_model_news',
    ],
    'GeorgRinger\\News\\Domain\\Model\\NewsExternal' => [
        'recordType' => '2',
        'tableName' => 'tx_news_domain_model_news',
    ],
    'GeorgRinger\\News\\Domain\\Model\\FileReference' => [
        'tableName' => 'sys_file_reference',
    ],
    'GeorgRinger\\News\\Domain\\Model\\TtContent' => [
        'tableName' => 'tt_content',
        'properties' => [
            'altText' => [
                'fieldname' => 'altText',
            ],
            'titleText' => [
                'fieldname' => 'titleText',
            ],
            'colPos' => [
                'fieldname' => 'colPos',
            ],
            'CType' => [
                'fieldname' => 'CType',
            ],
        ],
    ],
    'GeorgRinger\\News\\Domain\\Model\\Category' => [
        'tableName' => 'sys_category',
        'properties' => [
            'parentcategory' => [
                'fieldname' => 'parent',
            ],
        ],
    ],
    'ElementareTeilchen\\Adebisevents\\Domain\\Model\\FileReference' => [
        'tableName' => 'sys_file_reference',
    ],
    'ElementareTeilchen\\Adebisevents\\Domain\\Model\\Themenbereich' => [
        'properties' => [
            'key' => [
                'fieldname' => 'tx_adebisevents_key',
            ],
        ],
    ],
    'ElementareTeilchen\\Adebisevents\\Domain\\Model\\Kategorie' => [
        'properties' => [
            'key' => [
                'fieldname' => 'tx_adebisevents_key',
            ],
        ],
    ],
    'ElementareTeilchen\\Adebisevents\\Domain\\Model\\Pfarrei' => [
        'properties' => [
            'key' => [
                'fieldname' => 'tx_adebisevents_key',
            ],
        ],
    ],
    'ElementareTeilchen\\Adebisevents\\Domain\\Model\\Einrichtung' => [
        'properties' => [
            'key' => [
                'fieldname' => 'tx_adebisevents_key',
            ],
        ],
    ],
];
