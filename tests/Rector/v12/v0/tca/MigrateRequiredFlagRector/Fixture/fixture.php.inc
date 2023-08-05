<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateRequiredFlagRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'required_column1' => [
            'config' => [
                'eval' => 'trim,required',
            ],
        ],
        'required_column2' => [
            'config' => [
                'eval' => 'required',
            ],
        ],
        'required_column2_copy' => [
            'config' => [
                'type' => 'foo',
                'eval' => 'required',
            ],
        ],
        'already_required_column' => [
            'config' => [
                'eval' => 'required',
                'required' => true,
            ],
        ],
        'contact_person_salutation' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:legacy_extensions/Resources/Private/Language/locallang_db.xlf:tx_legacy_exntesion_domain_model_order.contact_person_salutation',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['-', 0],
                    [
                        'LLL:EXT:legacy_extensions/Resources/Private/Language/locallang.xlf:form.contactPersonSalutation.1.label',
                        1,
                    ],
                    [
                        'LLL:EXT:legacy_extensions/Resources/Private/Language/locallang.xlf:form.contactPersonSalutation.2.label',
                        2,
                    ],
                ],
                'size' => 1,
                'eval' => 'required',
                'maxitems' => 1,
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateRequiredFlagRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'required_column1' => [
            'config' => [
                'eval' => 'trim', 'required' => true,
            ],
        ],
        'required_column2' => [
            'config' => [
                'required' => true,
            ],
        ],
        'required_column2_copy' => [
            'config' => [
                'type' => 'foo',
                'required' => true,
            ],
        ],
        'already_required_column' => [
            'config' => [
                'required' => true,
            ],
        ],
        'contact_person_salutation' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:legacy_extensions/Resources/Private/Language/locallang_db.xlf:tx_legacy_exntesion_domain_model_order.contact_person_salutation',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['-', 0],
                    [
                        'LLL:EXT:legacy_extensions/Resources/Private/Language/locallang.xlf:form.contactPersonSalutation.1.label',
                        1,
                    ],
                    [
                        'LLL:EXT:legacy_extensions/Resources/Private/Language/locallang.xlf:form.contactPersonSalutation.2.label',
                        2,
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
                'required' => true,
            ],
        ],
    ],
];

?>
