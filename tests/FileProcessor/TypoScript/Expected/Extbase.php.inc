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
                'fieldName' => 'altText',
            ],
            'titleText' => [
                'fieldName' => 'titleText',
            ],
            'colPos' => [
                'fieldName' => 'colPos',
            ],
            'CType' => [
                'fieldName' => 'CType',
            ],
        ],
    ],
    'GeorgRinger\\News\\Domain\\Model\\Category' => [
        'tableName' => 'sys_category',
        'properties' => [
            'parentcategory' => [
                'fieldName' => 'parent',
            ],
        ],
    ],
];
